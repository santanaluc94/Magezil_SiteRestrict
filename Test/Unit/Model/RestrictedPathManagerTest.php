<?php

namespace Magezil\SiteRestrict\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Magezil\SiteRestrict\Model\RestrictedPathManager;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magezil\SiteRestrict\Model\Config\Source\SystemConfig;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Api\Data\StoreInterface;

class RestrictedPathManagerTest extends TestCase
{
    private MockObject|SystemConfig $systemConfig;
    private MockObject|StoreManagerInterface $storeManagerInterface;
    private MockObject|State $state;
    private MockObject|RestrictedPathManager $restrictedPathManagerMock;
    private MockObject|CustomerSession $customerSession;
    private array $fixedAllowedPaths = [
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

    protected function setUp(): void
    {
        $customerSessionFactory = $this->createMock(CustomerSessionFactory::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $this->storeManagerInterface = $this->createMock(StoreManagerInterface::class);
        $this->state = $this->createMock(State::class);

        $this->restrictedPathManagerMock = $this->getMockBuilder(RestrictedPathManager::class)
            ->onlyMethods(['__construct'])
            ->setConstructorArgs([
                $customerSessionFactory,
                $this->systemConfig,
                $this->storeManagerInterface,
                $this->state
            ])->getMock();

        $this->customerSession = $this->createMock(CustomerSession::class);
        $customerSessionFactory->method('create')->willReturn($this->customerSession);

        $storeMock = $this->createMock(StoreInterface::class);
        $storeMock->method('getId')->willReturn(1);
        $this->storeManagerInterface->method('getStore')->willReturn($storeMock);
    }

    /**
     * @dataProvider dataProviderPath
     */
    public function testIsPathAccessible(string $path): void
    {
        $this->systemConfig->method('getAvailablePaths')->willReturn($this->fixedAllowedPaths);
        $this->systemConfig->method('isModuleEnabled')->willReturn(true);

        $this->state->method('getAreaCode')->willReturn('frontend');

        $this->customerSession->method('isLoggedIn')->willReturn(false);

        $result = $this->restrictedPathManagerMock->isPathAccessible($path);

        $this->assertIsBool($result);
        $result ? $this->assertTrue($result) : $this->assertFalse($result);
    }

    public function dataProviderPath(): array
    {
        $fixedNotAllowedPaths = [
            'checkout_index_index',
            'checkout_cart_index',
            'cms_index_index',
            'catalog_product_index',
            'catalog_category_index',
        ];
        return [$this->fixedAllowedPaths, $fixedNotAllowedPaths];
    }

    public function testIsModuleEnable(): void
    {
        $this->systemConfig->method('isModuleEnabled')->willReturn(false);

        $result = $this->restrictedPathManagerMock->isPathAccessible('');

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }
}
