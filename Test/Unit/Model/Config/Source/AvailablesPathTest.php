<?php

namespace Magezil\SiteRestrict\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Magezil\SiteRestrict\Model\Config\Source\AvailablesPath;
use PHPUnit\Framework\MockObject\MockObject;

class AvailablesPathTest extends TestCase
{
    private MockObject|AvailablesPath $availablesPathMock;

    protected function setUp(): void
    {
        $this->availablesPathMock = $this->getMockBuilder(AvailablesPath::class)
            ->addMethods(['__construct'])
            ->setConstructorArgs([])
            ->getMock();
    }

    public function testToOptionArray(): void
    {
        $data = [
            [
                'value' => 'create',
                'label' => __('Create Account')
            ],
            [
                'value' => 'forgotpassword',
                'label' => __('Forgot Password')
            ]
        ];

        $result = $this->availablesPathMock->toOptionArray();
        $this->assertIsArray($result);
        $this->assertEquals($data, $result);
    }
}
