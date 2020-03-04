<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Model;

use Magento\Framework\Exception\LocalizedException;

/**
 * @method int getMessageId()
 * @method $this setMessageId(int $preferenceId)
 * @method int getAuthorId()
 * @method $this setAuthorId(int $customerId)
 * @method int getWebsiteId()
 * @method $this setWebsiteId(int $websiteId)
 * @method string getMessageText()
 * @method $this setMessageText(string $messageText)
 * @method string getChatHash()
 * @method $this setChatHash(string $chatHash)
 * @method string getCreatedAt()
 * @method $this setCreatedAt(string $createdAt)
 */
class Chat extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(\Alexeyb\Chat\Model\ResourceModel\Chat::class);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave(): self
    {
        parent::beforeSave();
        $this->validate();
        return $this;
    }

    /**
     * @throws LocalizedException
     */
    public function validate(): void
    {
        if (!$this->getAuthorId()) {
            throw new LocalizedException(__('Can\'t send message: %s is not set.', 'customer_id'));
        }

        if (!$this->getWebsiteId()) {
            throw new LocalizedException(__('Can\'t send message: %s is not set.', 'website_id'));
        }
    }
}
