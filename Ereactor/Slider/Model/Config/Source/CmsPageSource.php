<?php

namespace Ereactor\Slider\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;

class CmsPageSource implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
            [
                'label' => __('Please select'),
                'value' => null
            ]
        ];

        return array_merge($data, $this->collectionFactory->create()->toOptionIdArray());
    }
}
