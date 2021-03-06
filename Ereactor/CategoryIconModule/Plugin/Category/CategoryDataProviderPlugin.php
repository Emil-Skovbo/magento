<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace Ereactor\CategoryIconModule\Plugin\Category;

use Ereactor\CategoryIconModule\Api\CategoryUrlRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Category\DataProvider as CategoryDataProvider;

/**
 * CategoryDataProviderPlugin
 */
class CategoryDataProviderPlugin
{
    /**
     * @var CategoryUrlRepositoryInterface
     */
    private $categoryUrlRepository;

    /**
     * CategoryDataProviderPlugin constructor.
     *
     * @param CategoryUrlRepositoryInterface $categoryUrlRepository
     *
     * @internal param StoreManagerInterface $storeManager
     */
    public function __construct(CategoryUrlRepositoryInterface $categoryUrlRepository)
    {
        $this->categoryUrlRepository = $categoryUrlRepository;
    }

    /**
     * AfterGetData
     *
     * we need to modify the data Array returned and generate the data array.
     * This includes the correct image url.
     * Without the information the Admin will not show this field due to an error
     *
     * @param CategoryDataProvider $subject
     * @param array $data
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetData(CategoryDataProvider $subject, array $data)
    {
        /** @var Category $category */
        $category = $subject->getCurrentCategory();
        if (!$category) {
            error_log("fail category");
            return $data;
        }

        $attributeCodes = [
            'category_widget_icon',
        ];

        foreach ($attributeCodes as $attributeCode) {
            $image = $category->getData($attributeCode);
            if (!$image) {
                error_log("continue");
                continue;
            }
            error_log("after con");
            $imageName = $image;
            if (!is_string($image)) {
                error_log("is not string");
                if (is_array($image)) {
                    error_log("is array");
                    $imageName = $image[0]['name'];
                }
            }
            $categoryImageUrl = $this->categoryUrlRepository->getCategoryIconUrl($category, $attributeCode);
            error_log("url = " . $categoryImageUrl.$imageName);
            $seoImageData = [
                0 => [
                    'url' => $categoryImageUrl,
                    'name' => $imageName

                ],
            ];
            error_log(print_r($seoImageData));
            $data[$category->getId()][$attributeCode] = $seoImageData;
        }
        error_log(print_r($data, true));
        return $data;

    }

}
