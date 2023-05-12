<?php

namespace Magezil\SiteRestrict\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magezil\SiteRestrict\Model\RestrictedPathManager;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\ActionInterface;

class BeforeLoadController implements ObserverInterface
{
    public function __construct(
        private RestrictedPathManager $restrictedPathManager,
        private ActionFlag $actionFlag,
        private UrlInterface $urlBuilder,
        private HttpRequest $httpRequest,
        private HttpResponse $httpResponse
    ) {
    }

    public function execute(Observer $observer): void
    {
        $controllerActionPath = $this->httpRequest->getFullActionName();

        if (!$this->restrictedPathManager->isPathAccessible($controllerActionPath)) {
            $this->actionFlag->set(
                '',
                ActionInterface::FLAG_NO_DISPATCH,
                true  // @phpstan-ignore-line
            );

            $this->httpResponse->setRedirect(
                $this->urlBuilder->getUrl('customer/account/login')
            )->sendResponse();
        }
    }
}
