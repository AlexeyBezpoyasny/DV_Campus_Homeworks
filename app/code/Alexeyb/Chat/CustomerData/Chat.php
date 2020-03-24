<?php
declare(strict_types=1);

namespace Alexeyb\Chat\CustomerData;

use Alexeyb\Chat\Model\Chat as ChatModel;
use Alexeyb\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;

class Chat implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory
     */
    private $chatCollectionFactory;

    /**
     * Chat constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
    )
    {
        $this->customerSession = $customerSession;
        $this->chatCollectionFactory = $chatCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData(): array
    {
        /** @var ChatModel $chatModel */

        /** @var ChatCollection $chatCollection */
        $chatCollection = $this->chatCollectionFactory->create();
        $chatCollection->setOrder('message_id')
            ->setPageSize(10);

        if ($this->customerSession->isLoggedIn()) {
            $chatCollection->addFieldToFilter('author_id', (int)$this->customerSession->getCustomerId());

        } else {
            $chatCollection->addFieldToFilter('chat_hash', $this->customerSession->getChatHash());
        }
        foreach ($chatCollection as $chatModel) {
            $data[] = $chatModel->getMessageText();
        }
        return $data;
    }
}
