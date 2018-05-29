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

class Edit extends \LeanSwift\Scheduler\Controller\Adminhtml\Items
{

    public function execute()
    {
        /*amsmtp_clear_messages] => Array
    (
        [name] => amsmtp_clear_messages
        [instance] => Amasty\Smtp\Model\Logger\MessageLogger
    [method] => autoClear
    [schedule] => 0 1 * * *
        )*/
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('LeanSwift_Scheduler::scheduler');
        $resultPage->getConfig()->getTitle()->prepend(__('Cron Edit - Add'));

        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Magento\Cron\Model\Schedule');

        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend(__('Cron Edit - Edit'));
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('leanswift_scheduler/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_leanswift_scheduler_items', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('items_items_edit');
        $this->_view->renderLayout();
    }
}
