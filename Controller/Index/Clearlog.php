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

namespace LeanSwift\Logmanager\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use ZipArchive;
use LeanSwift\Logmanager\Helper\Data;


/**
 * Class Clearlog
 * @package LeanSwift\Logmanager\Controller
 */
class Clearlog extends Action
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * CreateSymlink constructor.
     * @param Context $context
     * @param Data $helper
     */
    public function __construct(Context $context, Data $helper)
    {
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return Data
     */
    protected function getHelper()
    {
        return $this->_helper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $type = $oldLogFile = $file = "";
        $logType = $this->getRequest()->getParams();
        if(!empty($logType) && array_key_exists('type', $logType)){
            $type = $logType['type']; // $type could be "ls" or "system"
        }
        $configList = DirectoryList::getDefaultConfig();
        //Log Directory path
        $filePath = BP . DIRECTORY_SEPARATOR . $configList[DirectoryList::VAR_DIR]['path'] . DIRECTORY_SEPARATOR . DirectoryList::LOG;
        //Files in Log directory
        $logFiles = scandir($filePath, 1);

        if (is_array($logFiles)) {
            foreach ($logFiles as $logFileName) {
                if ($dir = opendir($filePath)) {

                    //Skips the current iteration if the $logFileName is ., .., .htaccess
                    if ($logFileName == '.' || $logFileName == '..' || $logFileName == '.htaccess') {
                        continue;
                    }

                    $pathInfo = pathinfo($logFileName);
                    $fileExtension = $pathInfo['extension'];
                    $baseFileName = $pathInfo['filename'];

                    if($fileExtension == "zip"){
                        $oldLogFile = $logFileName;
                        $name = explode(".log",$baseFileName);
                        $logFileName = $name[0].'.log';
                    }

                    $checkLog = $this->_helper->checkType($type, $logFileName);
                    $file = $filePath.DIRECTORY_SEPARATOR .$logFileName;
                    if($checkLog){
                        if(file_exists($file)){
                            chdir($filePath);
                            try {
                                $zip = new ZipArchive();
                                $fileName = $logFileName . date("d-m-YH:i:s") . '.zip';
                                $fileName = str_replace([':', ' ', '-'], '', $fileName);

                                //Creating a ZIP file
                                if ($zip->open($filePath.DIRECTORY_SEPARATOR.$fileName, ZIPARCHIVE::CREATE) === false) {
                                    die ("An error occurred creating your ZIP file.");
                                }

                                $zip->addFile($logFileName);
                                $zip->close();
                                unlink($file);
                                echo "Cleared ".$logFileName."<br/>";

                            } catch (\Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    }
                }
            }
        }

    }
}