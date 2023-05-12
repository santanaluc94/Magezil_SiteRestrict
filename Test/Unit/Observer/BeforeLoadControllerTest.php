<?php

namespace Magezil\SiteRestrict\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Magezil\SiteRestrict\Observer\BeforeLoadController;
use Magezil\SiteRestrict\Model\RestrictedPathManager;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http as HttpResponse;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\Event\Observer;

class BeforeLoadControllerTest extends TestCase
{
    private MockObject|RestrictedPathManager $restrictedPathManagerMock;
    private MockObject|ActionFlag $actionFlagMock;
    private MockObject|UrlInterface $urlMock;
    private MockObject|HttpRequest $httpRequestMock;
    private MockObject|HttpResponse $httpResponseMock;
    private MockObject|BeforeLoadController $beforeLoadControllerMock;
    private MockObject|Observer $observerMock;

    protected function setUp(): void
    {
        $this->restrictedPathManagerMock = $this->createMock(RestrictedPathManager::class);
        $this->actionFlagMock = $this->createMock(ActionFlag::class);
        $this->urlMock = $this->createMock(UrlInterface::class);
        $this->httpRequestMock = $this->createMock(HttpRequest::class);
        $this->httpResponseMock = $this->createMock(HttpResponse::class);

        $this->beforeLoadControllerMock = $this->getMockBuilder(BeforeLoadController::class)
            ->onlyMethods(['__construct'])
            ->setConstructorArgs([
                $this->restrictedPathManagerMock,
                $this->actionFlagMock,
                $this->urlMock,
                $this->httpRequestMock,
                $this->httpResponseMock
            ])->getMock();

        $this->observerMock = $this->createMock(Observer::class);
    }

    public function testExecute(): void
    {
        $this->httpRequestMock->method('getFullActionName')->willReturn('customer_account_login');

        $this->restrictedPathManagerMock->method('isPathAccessible')->willReturn(false);

        $this->actionFlagMock->method('set');

        $this->urlMock->method('getUrl')->willReturn('https://localhost/customer/account/login');

        $this->httpResponseMock->method('setRedirect')->willReturnSelf();
        $this->httpResponseMock->method('sendResponse');

        $result = $this->beforeLoadControllerMock->execute($this->observerMock);
        $this->assertEmpty($result);
    }
}
