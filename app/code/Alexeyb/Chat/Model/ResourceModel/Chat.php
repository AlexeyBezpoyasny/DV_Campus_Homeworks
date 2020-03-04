<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Model\ResourceModel;

class Chat extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('alexeyb_chat', 'message_id');
    }
}
