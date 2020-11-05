<?php

declare(strict_types=1);

namespace Ereactor\Slider\Api\Data;

use Ereactor\Slider\Api\Data\EmployeeInterface;

interface SliderInterface
{
    public const STATUS = 'status';
    public const ENTITY_ID = 'entity_id';
    public const IMAGE_PATH = 'image_path';
    public const KEYWORDS = 'keywords';
    public const SORT_ORDER = 'sort_order';
    public const TYPE = 'type';
    public const URL_KEY = 'url_key';

    /**
     * @return int|null
     */
    public function getEntityId();

    /**
     * @param string|int $slider
     * @return SliderInterface
     */
    public function setEntityId($slider);

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @param string $type
     * @return SliderInterface
     */
    public function setType($type);

    /**
     * @param string|null $path
     * @return mixed
     */
    public function getImageUrl($path = null);

    /**
     * @return string|null
     */
    public function getImagePath();

    /**
     * @param string $path
     * @return SliderInterface
     */
    public function setImagePath($path);

    /**
     * @return string|null
     */
    public function getKeywords();

    /**
     * @param string $keywords
     * @return SliderInterface
     */
    public function setKeywords($keywords);

    /**
     * @return int|null
     */
    public function getSortOrder();

    /**
     * @param string|null $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * @return string|null
     */
    public function getUrlKey();

    /**
     * @param string|null $urlKey
     * @return $this
     */
    public function setUrlKey($urlKey);

    /**
     * @return string|null
     */
    public function getStatus();

    /**
     * @param string $status
     * @return SliderInterface
     */
    public function setStatus($status);
}
