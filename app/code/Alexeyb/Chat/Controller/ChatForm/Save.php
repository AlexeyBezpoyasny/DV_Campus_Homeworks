<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Controller\ChatForm;

use Alexeyb\Chat\Model\Chat;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DB\Transaction;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var \Alexeyb\Chat\Model\ChatFactory $chatFactory
     */
    private $chatFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Save constructor.
     * @param \Alexeyb\Chat\Model\ChatFactory $chatFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Alexeyb\Chat\Model\ChatFactory $chatFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->chatFactory = $chatFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Http $request */
        $request = $this->getRequest();

        /** @var Transaction $transaction */
        $transaction = $this->transactionFactory->create();

        try {
            /** @var Chat $chat */

            $inputMessage = $request->getParam('alexeyb_chat_enter_message');

            $chat = $this->chatFactory->create();

            $chat->setAuthorId(1)
                ->setWebsiteId((int)$this->storeManager->getWebsite()->getId())
                ->setMessageText($inputMessage)
                ->setChatHash(hash('md5', $inputMessage));
            $transaction->addObject($chat);

            $transaction->save();
            $message = __('Administrator will answer you within 5 minutes!');
        } catch (\Exception $e) {
            $message = __('Your message can\'t be sent. Please, wright to us at example@examle.com.');
        }

        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData([
            'message' => $message
        ]);
        return $resultJson;
    }
}
