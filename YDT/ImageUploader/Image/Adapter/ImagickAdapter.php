<?php

namespace YDT\ImageUploader\Image\Adapter;

use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\Adapter\ImageMagick;
use Psr\Log\LoggerInterface;
use YDT\ImageUploader\Helper\Config;

class ImagickAdapter extends ImageMagick
{
    protected $additionalMimeTypes =
        [
            'image/svg+xml',
            'image/svg',
        ];

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @var Config
     */
    private $imageUploaderHelper;

    /**
     * ImagickAdapter constructor.
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     * @param Mime $mime
     * @param Config $imageUploaderHelper
     * @param array $additionalMimeTypes
     * @param array $data
     */
    public function __construct(
        Filesystem $filesystem,
        LoggerInterface $logger,
        Mime $mime,
        Config $imageUploaderHelper,
        array $additionalMimeTypes = [],
        array $data = []
    ) {
        parent::__construct($filesystem, $logger, $data);

        $this->mime = $mime;
        $this->additionalMimeTypes = !empty($additionalMimeTypes) ? $additionalMimeTypes : $this->additionalMimeTypes;
        $this->imageUploaderHelper = $imageUploaderHelper;
    }

    /**
     * Add basic SVG validation
     *
     * @param string $filePath
     *
     * @return bool
     */
    public function validateUploadFile($filePath)
    {
        if ($this->imageUploaderHelper->isEnabled() &&
            in_array($this->mime->getMimeType($filePath), $this->additionalMimeTypes)) {
            return true;
        }

        return parent::validateUploadFile($filePath);
    }
}
