<?php 

/**
 * LeanSwift Connector Extension
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
 */

namespace LeanSwift\Logmanager\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	const XML_PATH_DIRECTORY_PATH 			=  'leanswift_logmanager/log_manager/directory_path';
	const XML_PATH_FLUSH_LOGFILES_PATH 		=  'leanswift_logmanager/log_manager/flush_log_files';
	const XML_PATH_LOGFILES_CONFIGURATION 	=  'leanswift_logmanager/log_manager/log_files_configuration';
	
	
	
	
	public function getStoreScope()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $storeScope;
    }
	
	public function getStoreConfig($storeConfigName, $storeScope = null, $storeId = null)
    {
        $storeScope = ($storeScope)?$storeScope:$this->getStoreScope();
        return $this->scopeConfig->getValue($storeConfigName, $storeScope, $storeId);
    }
	
	public function getLogFilesPath()
	{
        return $this->getStoreConfig(self::XML_PATH_DIRECTORY_PATH);
	}

	public function getLogFiles()
	{
		$list = '';
		$path = $this->getLogFilesPath();
		if($path)
		{
			chdir($path);
			$list = scandir($path);
		}
		return $list;
	}

	public function getNoOfDays()
	{
		return $this->getStoreConfig(self::XML_PATH_FLUSH_LOGFILES_PATH);
	}

	public function getLogFilesConfiguration()
	{
		$fileConfigurations = $this->getStoreConfig(self::XML_PATH_LOGFILES_CONFIGURATION);
        if ($fileConfigurations) 
        {
            $fileConfigArray = unserialize($fileConfigurations);
            return $fileConfigArray;
        }
        // no mapping or default is set up
        return null; 
	}
	
	public static function getLogFilePath()
	{
		$configList = DirectoryList::getDefaultConfig();
		return BP .DIRECTORY_SEPARATOR.$configList[DirectoryList::VAR_DIR]['path'].DIRECTORY_SEPARATOR.DirectoryList::LOG;
	}
}  