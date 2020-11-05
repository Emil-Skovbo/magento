<?php
namespace Ereactor\CategoryIconModule\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;


class CategoryData extends Template implements BlockInterface
{
    protected $_template = "widget/Aktuelt_old.phtml";
    protected $catRepo;
    protected $_categoryFactory;
    protected $CategoryRepository;

    public function __construct(
        Template\Context $context, array $data = [],
        CategoryRepositoryInterface $catRepo,
        CategoryRepository $categoryRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->validator = $context->getValidator();
        $this->resolver = $context->getResolver();
        $this->_filesystem = $context->getFilesystem();
        $this->templateEnginePool = $context->getEnginePool();
        $this->_appState = $context->getAppState();
        $this->templateContext = $this;
        $this->pageConfig = $context->getPageConfig();
        $this->catRepo = $catRepo;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
    }


// makes an array based on the input from widget.xml
    public function getCatIcon()
    {
        $catid = $this->getData("id");
        $catid = explode(",", $catid);
        $catinfo = [];
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        foreach ($catid as $id) {
            $categoryid = $this->catRepo->get($id);
            $categoryname = $this->_categoryFactory->create()->load($id);
            $catinfo2 = $categoryid->getCustomAttribute('category_widget_icon');
            $imgname = "";
            if ($catinfo2) {
                $imgname = $catinfo2->getValue();
            }
            $catinfo[] = array(
                'name' => $categoryname->getName(),
                //gets the url to the uploaded img
                'img' => $baseUrl . "catalog/category/tmp/category_widget_icon/" . $imgname,
                //gets the url to the category we are linking to
                'url' => $categoryname->getUrl()
            );
        }
        return $catinfo;
    }
}
?>
