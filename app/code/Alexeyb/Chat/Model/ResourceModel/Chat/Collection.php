<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Model\ResourceModel\Chat;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(
            \Alexeyb\Chat\Model\Chat::class,
            \Alexeyb\Chat\Model\ResourceModel\Chat::class
        );
    }
}
