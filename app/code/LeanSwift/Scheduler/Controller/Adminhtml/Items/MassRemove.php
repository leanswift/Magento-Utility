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

class MassRemove extends \LeanSwift\Scheduler\Controller\Adminhtml\Items
{

    public function execute()
    {
        $ids = $this->getRequest()->getParam('schedule_ids');
        $model = $this->_objectManager->create('Magento\Cron\Model\Schedule');
        foreach ($ids as $id){
            $model->load($id);
            if ($model->getId()) {
                $model->delete();
            }
        }
        $this->messageManager->addSuccess(__('Schedules Deleted.'));
        $this->_redirect('leanswift_scheduler/*');
    }
}