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

namespace LeanSwift\Logmanager\Block\Adminhtml;

use LeanSwift\Logmanager\Helper\Data as logManagerHelper;

class Instructions extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var logManagerHelper
     */
    protected $_logManagerHelper;

    /**
     * Instructions constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param logManagerHelper $logManagerHelper
     * @param \Magento\Framework\Url $url
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
        parent::__construct($context, $data);
    }

    /**
     * Get LogFilesPath from the Backend Configuration
     * @return mixed
     */
    private function getLogFilesPath()
    {
        return $this->_logManagerHelper->getLogFilesPath();
    }

    /**
     * Instructions to display in the Backend
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
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