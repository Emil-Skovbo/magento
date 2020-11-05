<?php

namespace YDT\ImageUploader\Plugin;

use Closure;
use Magento\Framework\File\Uploader;
use YDT\ImageUploader\Helper\Config;

class UploaderPlugin
{
    /**
     * @var Config
     */
    private $imageUploaderHelper;

    /**
     * UploaderPlugin constructor.
     * @param Config $imageUploaderHelper
     */
    public function __construct(
        Config $imageUploaderHelper
    ) {
        $this->imageUploaderHelper = $imageUploaderHelper;
    }

    public function aroundSetAllowedExtensions(
        Uploader $subject,
        Closure $proceed,
        $extensions = []
    ) {
        if ($this->imageUploaderHelper->isEnabled() &&
            !in_array('svg', $extensions)) {
            $extensions[] = 'svg';
        }

        return $proceed($extensions);
    }
}
