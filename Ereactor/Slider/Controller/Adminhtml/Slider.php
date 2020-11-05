<?php

declare(strict_types=1);

namespace Ereactor\Slider\Controller\Adminhtml;

abstract class Slider extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    const ADMIN_RESOURCE = 'Ereactor_Slider::top_level';

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Ereactor'), __('Ereactor'))
            ->addBreadcrumb(__('Slider'), __('Slider'));

        return $resultPage;
    }
}
