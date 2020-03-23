<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Controller\ChatForm;

use Alexeyb\Chat\Model\Chat;
use Alexeyb\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DB\Transaction;
use Magento\Framework\Exception\LocalizedException;

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
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory
     */
    private $chatCollectionFactory;
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * Save constructor.
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Alexeyb\Chat\Model\ChatFactory $chatFactory
     * @param \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Customer\Model\Session $customerSession,
        \Alexeyb\Chat\Model\ChatFactory $chatFactory,
        \Alexeyb\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->chatFactory = $chatFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->formKeyValidator = $formKeyValidator;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Http $request */
        $request = $this->getRequest();
        $inputMessage = $request->getParam('alexeyb_chat_enter_message');
        $authorId = (int) $this->customerSession->getCustomerId();
        $authorName = $this->customerSession->getCustomer()->getName();
        $chatHash = uniqid("", false);

        try {
            if (!$this->formKeyValidator->validate($request)) {
                throw new LocalizedException(__('Your session has expired'));
            }

            /** @var Chat $chat */
            $chat = $this->chatFactory->create();

            /** @var Transaction $transaction */
            $transaction = $this->transactionFactory->create();

            /** @var ChatCollection $chatCollection */
            $chatCollection = $this->chatCollectionFactory->create();

            $chat->setWebsiteId((int)$this->storeManager->getWebsite()->getId())
                ->setMessageText($inputMessage)
                ->setAuthorType('customer');

            if ($this->customerSession->isLoggedIn()) {
                $chatCollection->setOrder('chat_hash')
                    ->setPageSize(1)
                    ->addFieldToFilter('author_id', $authorId);
                $chatHashFromModel = $chatCollection->getData();

                $chat->setAuthorId($authorId)
                    ->setAuthorName($authorName);
                if ($chatHashFromModel) {
                    $chat->setChatHash($chatHashFromModel[0]['chat_hash']);
                } else {
                    $chat->setChatHash($chatHash);
                }
            } else {
                $chat->setAuthorId(2147483647);
                if (!$this->customerSession->getData('chat_hash')) {
                    $this->customerSession->setChatHash($chatHash);
                }
                $chat->setChatHash($this->customerSession->getChatHash());
            }
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
