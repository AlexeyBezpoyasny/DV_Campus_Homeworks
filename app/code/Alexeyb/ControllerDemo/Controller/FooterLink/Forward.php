<?php

namespace Alexeyb\ControllerDemo\Controller\FooterLink;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Forward extends Action implements HttpGetActionInterface
{
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward
            ->setParams(
                [
                    'first_name' => 'Alexey',
                    'second_name' => 'Bezpoyasny',
                    'repository_url' => 'https://github.com/AlexeyBezpoyasny'
                ]
            )->forward('data');
    }
}
