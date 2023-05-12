<?php

namespace Magezil\SiteRestrict\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Magezil\SiteRestrict\Model\Config\Source\SystemConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use PHPUnit\Framework\MockObject\MockObject;

class SystemConfigTest extends TestCase
{
    private MockObject|ScopeConfigInterface $scopeConfigMock;
    private MockObject|ModuleManager $moduleManagerMock;
    private MockObject|SystemConfig $systemConfigMock;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->moduleManagerMock = $this->createMock(ModuleManager::class);

        $this->systemConfigMock = $this->getMockBuilder(SystemConfig::class)
            ->onlyMethods(['__construct'])
            ->setConstructorArgs([
                $this->scopeConfigMock,
                $this->moduleManagerMock
            ])->getMock();
    }

    public function testIsModuleEnabled(): void
    {
        $this->moduleManagerMock->method('isEnabled')->willReturn(true);

        $this->scopeConfigMock->method('isSetFlag')->willReturn(true);

        $this->assertIsBool($this->systemConfigMock->isModuleEnabled());
        $this->assertTrue($this->systemConfigMock->isModuleEnabled());
    }

    public function testGetAvailablePaths(): void
    {
        $fixedAllowedPaths = [
            'customer_account_login',
            'customer_account_loginpost',
            'customer_account_logoutsuccess',
            'customer_account_confirm',
            'customer_account_confirmation',
            'customer_account_resetpasswordpost',
            'customer_section_load',
            'customer_account_create',
            'customer_account_createpost',
            'customer_account_forgotpassword',
            'customer_account_forgotpasswordpost'
        ];

        $this->moduleManagerMock->method('isEnabled')->willReturn(true);

        $this->scopeConfigMock->method('getValue')->willReturn('create,forgotpassword');

        $result = $this->systemConfigMock->getAvailablePaths();
        $this->assertIsArray($result);
        $this->assertEquals($fixedAllowedPaths, $result);
    }
}
