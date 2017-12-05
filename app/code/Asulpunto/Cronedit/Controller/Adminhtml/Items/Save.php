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

class Save extends \Asulpunto\Cronedit\Controller\Adminhtml\Items
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Magento\Cron\Model\Schedule');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );

                $data = $inputFilter->getUnescaped();
                $id=$data['schedule_id'];

                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }else{
                    unset ($data['schedule_id']);
                    $tz=$this->_objectManager->create('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
                    $data['created_at']=strftime('%Y-%m-%d %H:%M:%S', $tz->scopeTimeStamp());
                    $data['scheduled_at']=strftime('%Y-%m-%d %H:%M:00', $tz->scopeTimeStamp()+60);
                }
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('asulpunto_cronedit/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('asulpunto_cronedit/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('asulpunto_cronedit/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('asulpunto_cronedit/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('asulpunto_cronedit/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('asulpunto_cronedit/*/');
    }
}
