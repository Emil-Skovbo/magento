<?php

declare(strict_types=1);

namespace Ereactor\Slider\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ereactor\Slider\Api\Data\EmployeeInterface;
use Ereactor\Slider\Model\ResourceModel\Slider as SliderResource;
use Ereactor\Slider\Api\Data\SliderInterface;
use Ereactor\Slider\Api\Data\SliderInterfaceFactory;

class Slider extends AbstractModel implements SliderInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );

        $this->storeManager = $storeManager;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(SliderResource::class);
    }

    /**
     * @return string|null
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param string $faqCategoryId
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     */
    public function setEntityId($faqCategoryId)
    {
        return $this->setData(self::ENTITY_ID, $faqCategoryId);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @param string $type
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @return string|null
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * @param string $urlKey
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * @param null $path
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getImageUrl($path = null)
    {
        $pictureFile = $path ?: $this->getImagePath();

        if ($pictureFile) {
            return $this->storeManager
                    ->getStore()
                    ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $pictureFile;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getImagePath()
    {
        return $this->getData(self::IMAGE_PATH);
    }

    /**
     * @param string $path
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     */
    public function setImagePath($path)
    {
        return $this->setData(self::IMAGE_PATH, $path);
    }

    /**
     * @return string|null
     */
    public function getKeywords()
    {
        return $this->getData(self::KEYWORDS);
    }

    /**
     * @param string $keywords
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     */
    public function setKeywords($keywords)
    {
        return $this->setData(self::KEYWORDS, $keywords);
    }

    /**
     * @return string|null
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @param int|null $sortOrder
     * @return Slider
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @param string $status
     * @return \Ereactor\Slider\Api\Data\SliderInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
