<?php

declare(strict_types=1);

namespace Ereactor\Slider\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Ereactor\Slider\Api\Data\SliderSearchResultsInterfaceFactory;
use Ereactor\Slider\Api\SliderRepositoryInterface;
use Ereactor\Slider\Model\ResourceModel\Slider as ResourceSlider;
use Ereactor\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;

class SliderRepository implements SliderRepositoryInterface
{
    /**
     * @var ResourceSlider
     */
    protected $resource;

    /**
     * @var SliderCollectionFactory
     */
    protected $sliderCollectionFactory;

    /**
     * @var SliderSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var SliderFactory
     */
    protected $sliderFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    public function __construct(
        ResourceSlider $resource,
        SliderFactory $sliderFactory,
        SliderCollectionFactory $sliderCollectionFactory,
        SliderSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->sliderFactory = $sliderFactory;
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ereactor\Slider\Api\Data\SliderInterface $slider
    ) {
        try {
            $this->resource->save($slider);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the Slider: %1',
                    $exception->getMessage()
                )
            );
        }

        return $slider;
    }

    /**
     * {@inheritdoc}
     */
    public function get($sliderId)
    {
        $slider = $this->sliderFactory->create();
        $this->resource->load($slider, $sliderId);

        if (!$slider->getId()) {
            throw new NoSuchEntityException(__('Slider with id "%1" does not exist.', $sliderId));
        }

        return $slider;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->sliderCollectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Ereactor\Slider\Api\Data\SliderInterface $slider
    ) {
        try {
            $this->resource->delete($slider);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Slider: %1',
                    $exception->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($sliderId)
    {
        return $this->delete($this->get($sliderId));
    }
}
