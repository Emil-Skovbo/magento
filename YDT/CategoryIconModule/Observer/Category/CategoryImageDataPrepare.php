<?php
namespace Ereactor\CategoryIconModule\Observer\Category;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ImageDataPrepare
 */
class CategoryImageDataPrepare implements ObserverInterface
{
    /**
     * List of available cms page image attributes
     *
     * @var []
     */
    protected $imageAttributes = [
        'category_widget_icon',
    ];

    /**
     * Prepare image data before save
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        error_log("dataprep test: start");
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $observer->getCategory();
        $data = $observer->getRequest()->getParams();
        foreach ($this->imageAttributes as $attributeName) {
            error_log("dataprep test: 1");
            if (isset($data[$attributeName]) && is_array($data[$attributeName])) {
                error_log("dataprep test: 2");
                if (!empty($data[$attributeName]['delete'])) {
                    error_log("dataprep test: 3");
                    $data[$attributeName] = null;
                } else {
                    error_log("dataprep test: 4");
                    if (isset($data[$attributeName][0]['tmp_name'])) {
                        error_log("dataprep test: 5");
                        $data['is_uploaded'][$attributeName] = true;
                    }
                    if (isset($data[$attributeName][0]['name']) && isset($data[$attributeName][0]['tmp_name'])) {
                        error_log("dataprep test: 6");
                        $data[$attributeName] = $data[$attributeName][0]['name'];
                    } else {
                        error_log("dataprep test: 7");
                        unset($data[$attributeName]);
                    }
                }
            }
            if (isset($data[$attributeName])) {
                error_log("dataprep test: 8");
                $category->setData($attributeName, $data[$attributeName]);
            }
        }
        if (isset($data['is_uploaded'])) {
            error_log("dataprep test: 9");
            $category->setData('is_uploaded', $data['is_uploaded']);
        }
        error_log("dataprep test: end");
        return $this;
    }
}

