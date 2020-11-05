<?php

declare(strict_types=1);

namespace Ereactor\Slider\Controller\Adminhtml\Slider;

class Edit extends \Ereactor\Slider\Controller\Adminhtml\Slider
{
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->_objectManager->create(\Ereactor\Slider\Model\Slider::class);

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Slider no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('ereactor_slider_slider', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Slider') : __('New Slider'),
            $id ? __('Edit Slider') : __('New Slider')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Slider'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Slider %1', $model->getId()) : __('New Slider'));

        return $resultPage;
    }
}
