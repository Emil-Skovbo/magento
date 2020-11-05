<?php
namespace Ereactor\DropDownModule\Block\Widget;

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
    public function getButton()
    {

        return $this->getData("button");
    }
    public function getText()
    {
        return   $this->getData("text");
    }

}
?>
