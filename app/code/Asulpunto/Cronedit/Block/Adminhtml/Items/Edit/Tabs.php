<?php
/**
 * Copyright © 2015 Asulpunto. All rights reserved.
 */
namespace Asulpunto\Cronedit\Block\Adminhtml\Items\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('asulpunto_cronedit_items_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Schedule List'));
    }
}
