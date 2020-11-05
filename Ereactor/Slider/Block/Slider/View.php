<?php

namespace Ereactor\Slider\Block\Slider;

use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Ereactor\Slider\Model\ResourceModel\Slider\Collection;
use Ereactor\Slider\Api\Data\SliderInterface;
use Ereactor\Slider\Model\ResourceModel\Slider\Collection as SliderCollection;
use Ereactor\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;

class View extends Template
{
    /**
     * @var SliderCollectionFactory
     */
    private $sliderCollectionFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        Context $context,
        SliderCollectionFactory $sliderCollectionFactory,
        SerializerInterface $serializer,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->serializer = $serializer;
        $this->productRepository = $productRepository;
    }

    public function createCollection(): ?SliderCollection
    {
        return $this->sliderCollectionFactory->create()
            ->addOrder('sort_order', Collection::SORT_ORDER_ASC)
            ->addFieldToFilter('status', ['eq' => 1]);
    }

    /**
     * @return SliderCollection|SliderInterface[]
     */
    public function getCollection(): ?SliderCollection
    {
        return $this->getData('collection');
    }

    /**
     * @param SliderCollection|null $collection
     * @return $this
     */
    public function setCollection(SliderCollection $collection = null): self
    {
        return $this->setData('collection', $collection);
    }

    /**
     * @return bool
     */
    public function hasItems(): bool
    {
        return $this->getCollection() && $this->getCollection()->getSize();
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml(): self
    {
        $this->setCollection($this->createCollection());

        return parent::_beforeToHtml();
    }

    /**
     * @param $keywords
     * @return array|null
     */
    public function convertKeyWords($keywords)
    {
        return !empty($keywords) ? $this->serializer->unserialize($keywords) : null;
    }

    /**
     * @param $sku
     * @return ProductInterface|null
     */
    public function getProductBySku($sku)
    {
        try {
            $product = $this->productRepository->get($sku);
        } catch (Exception $exception) {
            return null;
        }

        return $product;
    }
}
