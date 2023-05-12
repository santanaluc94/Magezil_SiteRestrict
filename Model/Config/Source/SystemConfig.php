<?php

namespace Magezil\SiteRestrict\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Store\Model\ScopeInterface;

class SystemConfig
{
    private const REGISTRATION_MODULE_NAME = 'Magezil_SiteRestrict';
    private const CUSTOMER_ACCOUNT_BASE = 'customer_account_';
    private const SUFFIX_POST = 'post';

    // Config Paths
    private const IS_ENABLED = 'magezil_site_restrict/general/is_enabled';
    private const AVAILABLE_PATHS = 'magezil_site_restrict/general/available_paths';

    private array $fixedAllowedPaths = [
        'customer_account_login',
        'customer_account_loginpost',
        'customer_account_logoutsuccess',
        'customer_account_confirm',
        'customer_account_confirmation',
        'customer_account_resetpasswordpost',
        'customer_section_load'
    ];

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private ModuleManager $moduleManager,
        private RequestInterface $request
    ) {
    }

    public function isModuleEnabled(?int $storeId = null): bool
    {
        $isModuleEnabled = $this->moduleManager->isEnabled(self::REGISTRATION_MODULE_NAME);

        $isConfigEnabled = $this->scopeConfig->isSetFlag(
            self::IS_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $isModuleEnabled && $isConfigEnabled;
    }

    public function getAvailablePaths(?int $storeId = null): array
    {
        $availablePaths = explode(',', $this->scopeConfig->getValue(
            self::AVAILABLE_PATHS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));

        if (!empty($availablePaths)) {
            foreach ($availablePaths as $path) {
                $this->fixedAllowedPaths[] = self::CUSTOMER_ACCOUNT_BASE . $path;
                $this->fixedAllowedPaths[] = self::CUSTOMER_ACCOUNT_BASE . $path . self::SUFFIX_POST;
            }
        }

        return $this->fixedAllowedPaths;
    }
}
