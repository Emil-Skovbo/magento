<?php
/**
 * @copyright: Copyright © 2017 Firebear Studio. All rights reserved.
 * @author   : Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\ImportExport\Controller\Adminhtml\Job;

use Firebear\ImportExport\Controller\Adminhtml\Job as JobController;

/**
 * Class Check
 *
 * @package Firebear\ImportExport\Controller\Adminhtml\Job
 */
class Check extends JobController
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->resultFactory->create($this->resultFactory::TYPE_JSON);
        if ($this->getRequest()->isAjax()) {
            $file = $this->getRequest()->getParam('file');
            $job = $this->getRequest()->getParam('job');
            $result = $this->helper->countData($file, $job);
            return $resultJson->setData(
                [
                    'result' => $result
                ]
            );
        }
    }
}
