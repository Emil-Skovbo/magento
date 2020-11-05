<?php

declare(strict_types=1);

namespace Ereactor\Slider\Model\ResourceModel\Slider;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Ereactor\Slider\Model\Slider::class,
            \Ereactor\Slider\Model\ResourceModel\Slider::class
        );
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return $this->_toOptionArray('entity_id', 'title');
    }
}
