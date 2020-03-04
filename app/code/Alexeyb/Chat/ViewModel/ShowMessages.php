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
     * ShowMessages constructor.
     * @param \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     */

    public function __construct(
        \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
    ) {
        $this->chatCollectionFactory = $chatCollectionFactory;
    }

    public function getMessages()
    {
        /** @var ChatCollection $chatCollection */
        $chatCollection = $this->chatCollectionFactory->create();
        return $chatCollection->setOrder('message_id')
            ->setPageSize(10)
            ->addFieldToFilter('author_id', 1);
    }
}
