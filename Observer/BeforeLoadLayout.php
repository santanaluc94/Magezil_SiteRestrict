<?php

namespace Magezil\SiteRestrict\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\State;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Action\Action;

class BeforeLoadLayout implements ObserverInterface
{
    protected $request;
    protected $customerSession;
    protected $responseFactory;
    protected $actionFlag;
    protected $url;
    protected $state;
    protected $allowedPaths = [
        'customer_account_login',
        'customer_account_loginpost',
        'customer_account_create',
        'customer_account_createpost',
        'customer_account_logoutsuccess',
        'customer_account_confirm',
        'customer_account_confirmation',
        'customer_account_forgotpassword',
        'customer_account_forgotpasswordpost',
        'customer_account_createpassword',
        'customer_account_resetpasswordpost',
        'customer_section_load'
    ];

    public function __construct(
        RequestInterface $request,
        CustomerSession $customerSession,
        ResponseFactory $responseFactory,
        ActionFlag $actionFlag,
        UrlInterface $url,
        State $state
    ) {
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->responseFactory = $responseFactory;
        $this->actionFlag = $actionFlag;
        $this->url = $url;
        $this->state = $state;
    }

    public function execute(Observer $observer)
    {
        if (in_array($this->request->getFullActionName(), $this->allowedPaths)) {
            return true;
        }

        if ($this->state->getAreaCode() !== 'adminhtml') {
            $customerId = $this->customerSession->getId();

            if (empty($customerId)) {
                $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
                $this->responseFactory->create()
                    ->setRedirect(
                        $this->url->getUrl('customer/account/login')
                    )->sendResponse();
            }
        }
    }
}
