<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace LeanSwift\CustomerAttributes\Controller\Adminhtml\Customer\Attribute;

class Delete extends \LeanSwift\CustomerAttributes\Controller\Adminhtml\Customer\Attribute
{
    /**
     * Delete attribute action
     *
     * @return void
     */
    public function execute()
    {
        $attributeId = $this->getRequest()->getParam('attribute_id');
        if ($attributeId) {
            $attributeObject = $this->_initAttribute()->load($attributeId);
            if ($attributeObject->getEntityTypeId() != $this->_getEntityType()->getId() ||
                !$attributeObject->getIsUserDefined()
            ) {
                $this->messageManager->addError(__('You cannot delete this attribute.'));
                $this->_redirect('adminhtml/*/');
                return;
            }
            try {
                $attributeObject->delete();
                $this->_eventManager->dispatch(
                    'leanswift_customer_attributes_delete',
                    ['attribute' => $attributeObject]
                );

                $this->messageManager->addSuccess(__('You deleted the customer attribute.'));
                $this->_redirect('adminhtml/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('adminhtml/*/edit', ['attribute_id' => $attributeId, '_current' => true]);
                return;
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('We can\'t delete the customer address attribute right now.')
                );
                $this->_redirect('adminhtml/*/edit', ['attribute_id' => $attributeId, '_current' => true]);
                return;
            }
        }

        $this->_redirect('adminhtml/*/');
        return;
    }
}
