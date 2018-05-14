<?php
/**
 * LeanSwift eConnect Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the LeanSwift eConnect Extension License
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
 * @copyright   Copyright (c) 2018 LeanSwift Inc. (http://www.leanswift.com)
 * @license     http://www.leanswift.com/license/connector-extension
 */

namespace LeanSwift\Logmanager\Block\Adminhtml\System\Config\Form\Field;

use LeanSwift\Logmanager\Helper\Data as logManagerHelper;

class Logfileslist extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;
    /**
     * @var logManagerHelper
     */
    protected $_logManagerHelper;
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_yesNo;
    /**
     * @var bool
     */
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
        \Magento\Config\Model\Config\Source\Yesno $yesNo,
        array $data = []
    )
    {
        $this->_elementFactory = $elementFactory;
        $this->_logManagerHelper = $logManagerHelper;
        $this->_yesNo = $yesNo;
        parent::__construct($context, $data);
    }

    /**
     * Adding Headings and Button for Log Files Configuration
     */
    protected function _construct()
    {
        $this->addColumn('log_file', ['label' => __('Log File')]);
        $this->addColumn('max_size', ['label' => __('Maximum Size (in KB)'), 'style' => 'width:200px']);
        $this->addColumn('roll_over', ['label' => __('Roll Over')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
        parent::_construct();
    }

    /**
     * @param $columnName
     * @return mixed
     */
    public function renderCellTemplate($columnName)
    {
        if (isset($this->_columns[$columnName])) {
            switch ($columnName) {
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

    /**
     * @param $columnName
     * @param $options
     * @param bool $width
     * @return mixed
     */
    protected function _getSelectElementHtml($columnName, $options, $width = false)
    {
        $element = $this->_elementFactory->create('select');
        $element->setForm(
            $this->getForm()
        )->setName(
            $this->_getCellInputElementName($columnName)
        )->setHtmlId(
            $this->_getCellInputElementId('<%- _id %>', $columnName)
        )->setValues($options);

        if ($width) {
            $element->setStyle('width:' . $width);
        } else {
            $element->setStyle('width:300px');
        }
        return str_replace("\n", '', $element->getElementHtml());
    }

    /**
     * @param $columnName
     * @return mixed
     */
    public function getLogFileList($columnName)
    {
        $logFiles = $this->_logManagerHelper->getLogFiles();
        if (is_array($logFiles)) {
            $logFiles = array_filter($logFiles);
            foreach ($logFiles as $_logFile) {
                if (!is_null($_logFile) && (strpos($_logFile, 'log') != false)) {
                    $options[] = ['value' => $_logFile, 'label' => $_logFile];
                }
            }
        } else {
            $options[] = ['value' => '', 'label' => ('--Connection Error--')];
        }
        return $this->_getSelectElementHtml($columnName, $options);
    }

    /**
     * Returns the RollOverOption of log file
     * @param $columnName
     * @return mixed
     */
    public function getRolloverAction($columnName)
    {
        return $this->_getSelectElementHtml($columnName, $this->_yesNo->toOptionArray(), '150px');
    }
}