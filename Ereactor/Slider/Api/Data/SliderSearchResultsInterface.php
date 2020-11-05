<?php

declare(strict_types=1);

namespace Ereactor\Slider\Api\Data;

interface SliderSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Ereactor\Slider\Api\Data\SliderInterface[]
     */
    public function getItems();

    /**
     * @param \Ereactor\Slider\Api\Data\SliderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
