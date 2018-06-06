<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace LeanSwift\CustomerAttributes\Controller\Adminhtml\Customer\Attribute;

class NewAction extends \LeanSwift\CustomerAttributes\Controller\Adminhtml\Customer\Attribute
{
    /**
     * Create new attribute action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->addActionLayoutHandles();
        $this->_forward('edit');
    }
}
