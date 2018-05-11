<?php
/**
 * LeanSwift Marketplace Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the LeanSwift Connector Extension License
 * that is bundled with this package in the file LICENSE.txt located in the Connector Server.
 *
 * DISCLAIMER
 *
 * This extension is licensed and distributed by LeanSwift. Do not edit or add to this file
 * if you wish to upgrade Extension and Connector to newer versions in the future.
 * If you wish to customize Extension for your needs please contact LeanSwift for more
 * information. You may not reverse engineer, decompile,
 * or disassemble LeanSwift Connector Extension (All Versions), except and only to the extent that
 * such activity is expressly permitted by applicable law not withstanding this limitation.
 * 
 * @copyright   Copyright (C) Leanswift Solutions, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 * Terms and conditions http://leanswift.com/leanswift-eula/
 * @category LeanSwift
 * @package LeanSwift_Marketplace
 */
namespace LeanSwift\Logmanager\Block\Adminhtml\System\Config\Form\Field;


class LogFilePath extends \Magento\Config\Block\System\Config\Form\Field
{
	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
		if(!$element->getValue())
		{
			$element->setValue($this->_getLogFilePath());
		} 
		return $element->getElementHtml();
    }
	
	protected function _getLogFilePath()
	{
		return \LeanSwift\Logmanager\Helper\Data::getLogFilePath();
	}
}