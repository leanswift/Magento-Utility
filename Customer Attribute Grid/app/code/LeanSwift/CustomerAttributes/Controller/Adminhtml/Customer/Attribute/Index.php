<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace LeanSwift\CustomerAttributes\Controller\Adminhtml\Customer\Attribute;

class Index extends \LeanSwift\CustomerAttributes\Controller\Adminhtml\Customer\Attribute
{
    /**
     * Attributes grid
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Customer Attributes'));
        $this->_view->renderLayout();
    }
}
