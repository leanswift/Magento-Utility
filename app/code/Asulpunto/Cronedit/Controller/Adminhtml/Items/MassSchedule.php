<?php
/**
 * Magento Cronedit  by Asulpunto
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
 * @category    Asulpunto
 * @package     Asulpunto_Cronedit
 * @copyright   Copyright (c) 2016 Asulpunto (http://www.asulpunto.com)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 *
 */
namespace Asulpunto\Cronedit\Controller\Adminhtml\Items;

class MassSchedule extends \Asulpunto\Cronedit\Controller\Adminhtml\Items
{

    public function execute()
    {
        $ids = $this->getRequest()->getParam('schedule_ids');
        $model = $this->_objectManager->create('Magento\Cron\Model\Schedule');
        $tz=$this->_objectManager->create('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        foreach ($ids as $id){
            $model->load($id);
            $code=$model->getJobCode();
            $newModel= $this->_objectManager->create('Magento\Cron\Model\Schedule');
            $newModel->setJobCode($code);
            $newModel->setCreatedAt(strftime('%Y-%m-%d %H:%M:%S', $tz->scopeTimeStamp()));
            $newModel->setScheduledAt(strftime('%Y-%m-%d %H:%M:00', $tz->scopeTimeStamp()+60));
            $newModel->save();
        }
        $this->messageManager->addSuccess(__('Schedules Added.'));
        $this->_redirect('asulpunto_cronedit/*');
    }
}