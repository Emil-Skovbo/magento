<?php

declare(strict_types=1);

namespace Ereactor\Slider\Controller\Adminhtml\Slider;

class Delete extends \Ereactor\Slider\Controller\Adminhtml\Slider
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                $model = $this->_objectManager->create(\Ereactor\Slider\Model\Slider::class);
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted Slider.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find the Slider to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
