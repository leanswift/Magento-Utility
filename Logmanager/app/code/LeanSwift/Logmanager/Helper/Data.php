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

namespace LeanSwift\Logmanager\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Const
     */
    const XML_PATH_DIRECTORY_PATH = 'leanswift_logmanager/log_manager/directory_path';
    /**
     * Const
     */
    const XML_PATH_FLUSH_LOGFILES_PATH = 'leanswift_logmanager/log_manager/flush_log_files';
    /**
     * Const
     */
    const XML_PATH_LOGFILES_CONFIGURATION = 'leanswift_logmanager/log_manager/log_files_configuration';

    /**
     * Returns the scope of the Store
     * @return mixed
     */
    public function getStoreScope()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $storeScope;
    }

    public function getStoreConfig($storeConfigName, $storeScope = null, $storeId = null)
    {
        $storeScope = ($storeScope) ? $storeScope : $this->getStoreScope();
        return $this->scopeConfig->getValue($storeConfigName, $storeScope, $storeId);
    }

    /**
     * Get LogFilesPath from the Backend Configuration
     * @return mixed
     */
    public function getLogFilesPath()
    {
        return $this->getStoreConfig(self::XML_PATH_DIRECTORY_PATH);
    }

    /**
     * @return array|string
     */
    public function getLogFiles()
    {
        $list = '';
        $path = $this->getLogFilesPath();
        if ($path) {
            chdir($path);
            $list = scandir($path);
        }
        return $list;
    }

    /**
     * Get the No. of days to flush the log files
     * @return mixed
     */
    public function getNoOfDays()
    {
        return $this->getStoreConfig(self::XML_PATH_FLUSH_LOGFILES_PATH);
    }

    /**
     * Get the Log File Configuration from the Backend
     * @return mixed|null
     */
    public function getLogFilesConfiguration()
    {
        $fileConfigurations = $this->getStoreConfig(self::XML_PATH_LOGFILES_CONFIGURATION);
        if ($fileConfigurations) {
            $fileConfigArray = unserialize($fileConfigurations);
            return $fileConfigArray;
        }
        // no mapping or default is set up
        return null;
    }

    /**
     * returns the full path of log folder
     * @return string
     */
    public static function getLogFilePath()
    {
        $configList = DirectoryList::getDefaultConfig();
        return BP . DIRECTORY_SEPARATOR . $configList[DirectoryList::VAR_DIR]['path'] . DIRECTORY_SEPARATOR . DirectoryList::LOG;
    }
}  