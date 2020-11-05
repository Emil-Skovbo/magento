<?php

declare(strict_types=1);

namespace Ereactor\Slider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SliderRepositoryInterface
{
    /**
     * @param \Ereactor\Slider\Api\Data\SliderInterface $slider
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Ereactor\Slider\Api\Data\SliderInterface $slider
    );

    /**
     * @param string $sliderId
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($sliderId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ereactor\Slider\Api\Data\SliderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * @param \Ereactor\Slider\Api\Data\SliderInterface $slider
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Ereactor\Slider\Api\Data\SliderInterface $slider
    );

    /**
     * @param string $sliderId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($sliderId);
}
