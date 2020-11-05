<?php
namespace Dev98\CategoryAttributes\Controller\Adminhtml\Category;

use Magento\Framework\Controller\ResultFactory;

class CategoryImageUpload extends \Magento\Backend\App\Action
{
    protected $imageUploader;
    protected $baseTmpPath;
    protected $basePath;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     */
    public function __construct(

        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        $baseTmpPath,
        $basePath
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Catalog::categories');
    }
/**
* Upload file controller action
*
* @return \Magento\Framework\Controller\ResultInterface
*/
public function execute()
{
try {
$attributeCode = $this->getRequest()->getParam('attribute_code');
if (!$attributeCode) {
throw new \Exception('attribute_code missing');
}

$basePath = 'catalog/category/dev98/' . $attributeCode;
$baseTmpPath = 'catalog/category/dev98/tmp/' . $attributeCode;

$this->imageUploader->setBasePath($basePath);
$this->imageUploader->setBaseTmpPath($baseTmpPath);

$result = $this->imageUploader->saveFileToTmpDir($attributeCode);

$result['cookie'] = [
'name' => $this->_getSession()->getName(),
'value' => $this->_getSession()->getSessionId(),
'lifetime' => $this->_getSession()->getCookieLifetime(),
'path' => $this->_getSession()->getCookiePath(),
'domain' => $this->_getSession()->getCookieDomain(),
];
} catch (\Exception $e) {
$result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
}

return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
}
}
