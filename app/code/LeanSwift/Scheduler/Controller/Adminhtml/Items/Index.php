<?php
/**
 * Magento Scheduler  by LeanSwift
 *
 * NOTICE OF LICENSE
 *  This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, Version 3 of the License. You can view
 *   the license here http://opensource.org/licenses/GPL-3.0

 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 * @category    LeanSwift
 * @package     LeanSwift_Scheduler
 * @copyright   Copyright (c) 2017 LeanSwift (http://www.leanswift.com)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 *
 */

namespace LeanSwift\Scheduler\Controller\Adminhtml\Items;

class Index extends \LeanSwift\Scheduler\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->_checkCron();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('LeanSwift_Scheduler::scheduler');
        $resultPage->getConfig()->getTitle()->prepend(__('LS Scheduler'));
        return $resultPage;
    }

    private function _checkCron(){
        $schedules= $this->_objectManager->create('Magento\Cron\Model\Schedule')->getCollection();
        $schedules->addFieldToFilter('job_code','leanswift_cron_check');
        $schedules->addFieldToFilter('status','success');
        $foundRecently=0;
        $tz= $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        $date=$tz->date()->format('Y-m-d H:i:s');
        foreach ($schedules as $schedule){
            $f=$schedule->getFinishedAt();
            //$f=\Magento\Framework\Stdlib\DateTime\DateTime::date($finished);
            $date1= \DateTime::createFromFormat('Y-m-d H:i:s',$f);
            $date2= \DateTime::createFromFormat('Y-m-d H:i:s',$date);
            $minutes=(abs($date2->getTimestamp())-abs($date1->getTimestamp()))/60;
            //$this->messageManager->addError($f.' X  '.$date . 'X '. $minutes);
            $foundRecently=1;
        }

        if (!$foundRecently || $minutes >30){
            $this->messageManager->addError(
                $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml('Cron did not run for the last 30 minutes.')
            );
        }else{
            $this->messageManager->addSuccess(
                $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml('Cron is running regurally.'));
        }

    }
}
