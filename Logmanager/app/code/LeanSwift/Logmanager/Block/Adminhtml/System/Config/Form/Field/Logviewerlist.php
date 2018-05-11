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

use LeanSwift\Logmanager\Helper\Data as logManagerHelper;

use Magento\Framework\App\Filesystem\DirectoryList;


class Logviewerlist extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    
    protected $_logManagerHelper;
	protected $_url;
	
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        logManagerHelper $logManagerHelper,
		\Magento\Framework\Url $url,
        array $data = []
    )
    {
        $this->_logManagerHelper = $logManagerHelper;
		$this->_url = $url;
        parent::__construct($context,$data);
		
    }
	
	public function getBaseURL()
	{
		return $this->_url->getBaseUrl();
	}
	
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
		$logFiles = $this->getLogFiles();
		$getLogUrl = $this->getLogFilesPath();
		return $this->__getHtml($logFiles, $getLogUrl);
	}
	
	protected function getLogUrl($_logFile=false)
	{
		if($_logFile)
		{
			return $this->getBaseURL().DirectoryList::VAR_DIR.'/'.DirectoryList::LOG.'/'.$_logFile;
		}
		else
		{
			return $this->getBaseURL().DirectoryList::VAR_DIR.'/'.DirectoryList::LOG;
		}
	}

	protected function __getHtml($logFiles, $getLogUrl)
	{
		$rendered = '';
		if(is_array($logFiles))
		{
			$rendered = '';
			$i = 1;
			foreach ($logFiles as $_logFile)
			{
				$isAccesible = '';
				if(!is_null($_logFile) && (strpos($_logFile, 'log') != false) && (strpos($_logFile, 'zip') != true))
				{
					$logUrl =   $this->getLogUrl($_logFile);
					$rendered .= '<tr class="logviewlist" id="addRow-'.$i.'">';
					$rendered .= '<td>';
					$rendered .= '<label>'.$_logFile.'</label>';
					$rendered .= '</td>';
					$rendered .= '<td>';
					$rendered .= '<a id="'.$i.'" target="_blank" href="'.$logUrl.'">View</a>';
					$rendered .= '</td>';
					$rendered .= '<td>';
					$rendered .= '<a href="'.$logUrl.'"  download>Download</a>';
					$rendered .= '</td>';
					
					$rendered .= '<td>';
					$rendered .= '<input type="checkbox" name="logFiles[]" value="'.$_logFile.'"> Delete';
					$rendered .= '</td>';
					$rendered .= '</tr>';
					$i++;
				}
			}
		}
		//$rendered .= '<p class="note"><span>'.__(sprintf('The files present in the path <b>%s</b> be authorized to view and download', $getLogUrl)).'</span></p>';
		return $rendered;
	}
	
	
	private function getLogFiles()
	{
		return $this->_logManagerHelper->getLogFiles();
	}
	
	private function getLogFilesPath()
	{
		return \LeanSwift\Logmanager\Helper\Data::getLogFilePath();
	}
}