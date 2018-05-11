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
namespace LeanSwift\Logmanager\Block\Adminhtml;

use LeanSwift\Logmanager\Helper\Data as logManagerHelper;

class Instructions extends \Magento\Config\Block\System\Config\Form\Field
{
	protected $_logManagerHelper;
	
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        logManagerHelper $logManagerHelper,
		\Magento\Framework\Url $url,
        array $data = []
    )
    {
        $this->_logManagerHelper = $logManagerHelper;
        parent::__construct($context,$data);
		
    }
	
	private function getLogFilesPath()
	{
		return $this->_logManagerHelper->getLogFilesPath();
	}
	
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
		$getLogUrl = $this->getLogFilesPath();
		$html = '';
		$html .= '<ul class="log-instructions">';
		$html .= '<li>';
		$html .= __("To be able to view/download log files, 'var' folder should have 755 permission.");
		$html .= '</li>';
		$html .= '<li>';
		$html .= __("If the files are still not accessible, set 'Allow from all' instead of 'Deny from all' in the .htaccess file under 'var' directory.");
		$html .= '</li>';
		$html .= '</ul>';
		return $html;
	}
}