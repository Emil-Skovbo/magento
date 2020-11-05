<?php

namespace Ereactor\Slider\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class TypeSource implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            0 => [
                'label' => __('Please select'),
                'value' => null
            ],
            1 => [
                'label' => __('CMS Page'),
                'value' => 'cms_page'
            ],
            2  => [
                'label' => __('Gallery'),
                'value' => 'gallery'
            ],
        ];
    }
}
