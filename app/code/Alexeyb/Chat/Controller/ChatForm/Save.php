<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Controller\ChatForm;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData([
            'message' => 'Administrator will answer you within 5 minutes'
        ]);
        return $resultJson;
    }
}
