<?php

declare(strict_types=1);

namespace Ereactor\Slider\Model\Config\Source;

use Magento\Framework\Data\Collection;
use Magento\Framework\Data\OptionSourceInterface;
use Ereactor\Slider\Model\ResourceModel\Slider\CollectionFactory;

class SliderSource implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('status', ['eq' => 1]);

        return $collection->toOptionArray();
    }
}
