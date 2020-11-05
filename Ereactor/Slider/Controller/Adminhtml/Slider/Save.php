<?php

declare(strict_types=1);

namespace Ereactor\Slider\Controller\Adminhtml\Slider;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ereactor\Slider\Api\Data\SliderInterface;
use Ereactor\Slider\Api\SliderRepositoryInterface;
use Ereactor\Slider\Model\SliderFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var SliderRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var SliderFactory
     */
    private $categoryFactory;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param SliderRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        ImageUploader $imageUploader,
        SerializerInterface $serializer,
        SliderRepositoryInterface $categoryRepository,
        SliderFactory $categoryFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);

        $this->dataPersistor = $dataPersistor;
        $this->categoryRepository = $categoryRepository;
        $this->categoryFactory = $categoryFactory;
        $this->imageUploader = $imageUploader;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');

            if ($id) {
                try {
                    $category = $this->categoryRepository->get($id);
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('This Slider no longer exists.'));
                    $this->_redirect('*/*/');
                }
            }

            if (!isset($category)) {
                $category = $this->categoryFactory->create();
            }

            $data['image_path'] = $this->saveImage($data['image'] ?? null, $data['image_path'] ?? null);

            foreach ($data as $attribute => $value) {
                if (SliderInterface::KEYWORDS === $attribute && is_array($value) && !empty($value)) {
                    foreach ($value as &$keyWord) {
                        if (isset($keyWord['image']) && is_array($keyWord['image']) && $this->isTmpFileAvailable($data['image'])) {
                            $newPath = $this->saveImage($keyWord['image'], $keyWord['image']);
                            $keyWord['image'][0]['url'] = $this->storeManager
                                ->getStore()
                                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $newPath;

                            unset($keyWord['image'][0]['tmp_name']);
                        }
                    }

                    $value = $this->serializer->serialize($value);
                }

                $category->setDataUsingMethod($attribute, $this->isAllowed($data['type'], $attribute) ? $value : null);
            }

            try {
                $this->categoryRepository->save($category);
                $this->messageManager->addSuccessMessage(__('You saved the Slider.'));
                $this->dataPersistor->clear('ereactor_slider_slider');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $category->getEntityId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Slider.')
                );
            }

            $this->dataPersistor->set('ereactor_slider_slider', $data);

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $value
     * @param $originalValue
     * @return false|string|null
     * @throws LocalizedException
     */
    protected function saveImage($value, $originalValue)
    {
        if ($imageName = $this->getUploadedImageName($value)) {
            if ($this->isTmpFileAvailable($value)) {
                return $this->imageUploader->moveFileFromTmp($imageName, true);
            }

            if (strpos($value[0]['url'], '/pub/media/') === 0) {
                return substr($value[0]['url'], strlen('/pub/media/'));
            }

            if (strpos($value[0]['url'], '/media/') === 0) {
                return substr($value[0]['url'], strlen('/media/'));
            }

            return $originalValue;
        }
        return null;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isTmpFileAvailable($value): bool
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    /**
     * @param $value
     * @return string
     */
    private function getUploadedImageName($value): string
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }
        return '';
    }

    /**
     * @param $type
     * @param $value
     * @return bool
     */
    protected function isAllowed($type, $value)
    {
        $ignoredFields = [
            'cms_page' => [
                'title',
                'keywords'
            ],
            'gallery' => [
                'url_key',
            ],
        ];

        return isset($ignoredFields[$type]) ? !(in_array($value, $ignoredFields[$type])) : true;
    }
}
