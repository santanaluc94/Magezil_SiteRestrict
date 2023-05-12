<?php

namespace Magezil\SiteRestrict\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class AvailablesPath implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'value' => 'create',
                'label' => __('Create Account')
            ],
            [
                'value' => 'forgotpassword',
                'label' => __('Forgot Password')
            ]
        ];
    }
}
