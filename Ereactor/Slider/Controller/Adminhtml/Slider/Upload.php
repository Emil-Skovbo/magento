<?php

namespace Ereactor\Slider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Upload extends Action
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Upload action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');

        preg_match("/^(.*?)\[(\d)\]\[(.*?)\]$/", $imageId, $file);

        if (count($file) === 4 && isset($_FILES[$file[1]])) {
            foreach ($_FILES[$file[1]] as &$value) {
                if (isset($value[$file[2]]) && isset($value[$file[2]][$file[3]])) {
                    $value[$file[2]] = $value[$file[2]][$file[3]];
                }
            }
            $imageId = str_replace('[image]', '', $imageId);
        }

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
