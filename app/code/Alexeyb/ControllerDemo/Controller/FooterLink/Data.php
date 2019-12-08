<?php

namespace Alexeyb\ControllerDemo\Controller\FooterLink;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Data extends Action implements HttpGetActionInterface
{
    public function execute()
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $block = $page->getLayout()->getBlock('controller.demo.block.info');
        if ($block) {
            $block->setData(
                [
                    'first_name' => $this->getRequest()->getParam('first_name'),
                    'second_name' => $this->getRequest()->getParam('second_name'),
                    'repository_url' => $this->getRequest()->getParam('repository_url'),
                ]
            );
        }

        return $page;
    }
}
