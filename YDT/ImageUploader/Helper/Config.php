<?php

namespace YDT\ImageUploader\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    public const XML_PATH_IMAGE_UPLOADER_IS_ENABLED = 'sono_general_settings/file_settings/enable_additional_extensions';

    /**
     * @return int
     */
    public function isEnabled()
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_IMAGE_UPLOADER_IS_ENABLED);
    }
}
