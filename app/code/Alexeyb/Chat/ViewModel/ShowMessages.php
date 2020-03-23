<?php
declare(strict_types=1);

namespace Alexeyb\Chat\ViewModel;

use Alexeyb\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;

class ShowMessages implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     */
    private $chatCollectionFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * ShowMessages constructor.
     * @param \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */

    public function __construct(
        \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * upgrade values when customer login
     */
    public function upgradeValuesWhenLoggedIn()
    {
        /** @var ChatCollection $chatCollection */
        $authorId = (int) $this->customerSession->getCustomerId();
        $authorName = (string) $this->customerSession->getCustomer()->getName();

        $chatCollection = $this->chatCollectionFactory->create();
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getData('chat_hash')) {
            $chatCollection->addFieldToFilter('chat_hash', $this->customerSession->getData('chat_hash'))
                ->addFieldToFilter('author_id', 2147483647);
            foreach ($chatCollection as $item) {
                $item->setChatHash($chatCollection->getData()[0]['chat_hash'])
                    ->setAuthorId($authorId)
                    ->setAuthorName($authorName);
                $item->save();
            }
            $this->customerSession->setChatHash(null);
        }
        return $chatCollection;
    }

    public function getMessages(): ChatCollection
    {
        $this->upgradeValuesWhenLoggedIn();
        /** @var ChatCollection $chatCollection */
        $chatCollection = $this->chatCollectionFactory->create();
        $chatCollection->setOrder('message_id')
            ->setPageSize(10);

        if ($this->customerSession->isLoggedIn()) {
            $authorId = (int) $this->customerSession->getCustomerId();
            $chatCollection->addFieldToFilter('author_id', $authorId);
        } else {
            $chatHash = $this->customerSession->getChatHash();
            $chatCollection->addFieldToFilter('chat_hash', $chatHash);
        }

        return $chatCollection;
    }
}
