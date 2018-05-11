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

class Logfileslist extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;
    
    protected $_logManagerHelper;
	
	protected $_Yesno;
	
	protected $_advanced = true;
	
	 

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        logManagerHelper $logManagerHelper,
		\Magento\Config\Model\Config\Source\Yesno $Yesno,
        array $data = []
    )
    {
        $this->_elementFactory  = $elementFactory;
        $this->_logManagerHelper = $logManagerHelper;
		$this->_Yesno = $Yesno;
        parent::__construct($context,$data);
    }
    
    protected function _construct()
    {   
        $this->addColumn('log_file', ['label' => __('Log File')]);
        $this->addColumn('max_size', ['label' => __('Maximum Size (in KB)'), 'style' => 'width:200px']);
		$this->addColumn('roll_over', ['label' => __('Roll Over')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
        parent::_construct();
    }
	
	public function renderCellTemplate($columnName)
    {
		if (isset($this->_columns[$columnName])) {
			switch($columnName)
			{
				case 'log_file':
					return $this->getLogFileList($columnName);
					break;
				case 'roll_over':
					return $this->getRolloverAction($columnName);
					break;
			}
		}
        return parent::renderCellTemplate($columnName);
    }
	
	protected function _getSelectElementHtml($columnName, $options, $width=false)
	{
		$element = $this->_elementFactory->create('select');
            $element->setForm(
					$this->getForm()
				)->setName(
                    $this->_getCellInputElementName($columnName)
                )->setHtmlId(
                    $this->_getCellInputElementId('<%- _id %>', $columnName)
                )->setValues($options);
			
			if($width)
			{
				$element->setStyle('width:'.$width);
			}
			else
			{
				$element->setStyle('width:300px');
			}
		return str_replace("\n", '', $element->getElementHtml());
	} 
	
	public function getLogFileList($columnName)
	{
		$logFiles = $this->_logManagerHelper->getLogFiles();
		if(is_array($logFiles))
		{
			$logFiles = array_filter($logFiles);
			foreach ($logFiles as $_logFile) {
				if(!is_null($_logFile) && (strpos($_logFile, 'log') != false))
				{
					$options[] = ['value'=>$_logFile,'label'=>$_logFile];
				}
			}
		}
		else{
			$options[] = ['value'=>'','label'=> ('--Connection Error--')];
		}
		return $this->_getSelectElementHtml($columnName, $options);
	}
	
	public function getRolloverAction($columnName)
	{
		return $this->_getSelectElementHtml($columnName, $this->_Yesno->toOptionArray(),'150px');
	}
}