<?php

namespace Magezil\SiteRestrict\Model;

use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magezil\SiteRestrict\Model\Config\Source\SystemConfig;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Api\Data\StoreInterface;

class RestrictedPathManager
{
    public function __construct(
        protected CustomerSessionFactory $customerSessionFactory,
        protected SystemConfig $systemConfig,
        protected StoreManagerInterface $storeManager,
        protected State $state
    ) {
    }

    public function isPathAccessible(string $path): bool
    {
        $customerSession = $this->customerSessionFactory->create();
        $storeId = (int) $this->getStore()->getId();

        if (
            !$this->systemConfig->isModuleEnabled($storeId) ||
            $this->state->getAreaCode() === Area::AREA_ADMINHTML ||
            $customerSession->isLoggedIn()
        ) {
            return true;
        }

        $availablePaths = $this->systemConfig->getAvailablePaths($storeId);

        if (!in_array($path, $availablePaths)) {
            return false;
        }

        return true;
    }

    protected function getStore(): StoreInterface
    {
        return $this->storeManager->getStore();
    }
}
