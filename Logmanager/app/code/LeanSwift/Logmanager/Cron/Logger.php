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

namespace LeanSwift\Logmanager\Cron;

use LeanSwift\Logmanager\Helper\Data;
use ZipArchive;

class Logger
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Logger constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * @return Data
     */
    protected function getHelper()
    {
        return $this->_helper;
    }

    /**
     * @return array|string
     */
    protected function getLogFiles()
    {
        return $this->getHelper()->getLogFiles();
    }

    /**
     * Calculates the days and flushes the logs
     */
    protected function flushLogsOnDays()
    {
        $os = PHP_OS;
        $filePath = $this->getHelper()->getLogFilesPath();
        $listOfLogs = $this->getLogFiles();
        $noOfDays = $this->getHelper()->getNoOfDays();

        if (!empty($noOfDays)) {
            if ($dir = opendir($filePath)) {
                while (false !== ($logFile = readdir($dir))) {
                    chdir($filePath);

                    if (strpos($logFile, 'log') != false) {

                        if (strtoupper(strpos($os, 'win')) != false)
                            $fileTime = filectime($logFile);
                        else
                            $fileTime = filemtime($logFile);

                        if ((time() - $fileTime) >= $noOfDays * 24 * 60 * 60) {
                            unlink($logFile);
                        }

                    }

                }
                closedir($dir);
            }
        }

    }

    /**
     * Archiving the log files based on File size
     */
    protected function rollOverLogs()
    {
        $filePath = $this->getHelper()->getLogFilesPath();
        $logFileConfigurations = $this->getHelper()->getLogFilesConfiguration();

        //change the current working directory to the log located folder
        if (is_array($logFileConfigurations)) {
            foreach ($logFileConfigurations as $configuration) {
                if ($dir = opendir($filePath)) {
                    $file = $configuration['log_file'];

                    if (file_exists($file)) {
                        $configuredSize = $configuration['max_size'];
                        $isRollOver = $configuration['roll_over'];
                        $filesize = (filesize($file) / (1024));// bytes to KB

                        if ($isRollOver && ($filesize >= $configuredSize)) {
                            chdir($filePath);
                            try {
                                $zip = new ZipArchive();
                                $fileName = $configuration['log_file'] . date("d-m-YH:i:s") . '.zip';
                                $fileName = str_replace(" ", "", $fileName);

                                if ($zip->open($fileName, ZIPARCHIVE::CREATE) === false) {
                                    die ("An error occurred creating your ZIP file.");
                                }

                                $zip->addFile($file);
                                $zip->close();
                                unlink($file);
                            } catch (\Exception $e) {
                                echo $e->getMessage();
                            }
                        }

                    }
                    
                }
            }
        }
    }

    /**
     * Manages the log by flushing and archiving the files
     */
    public function manageLog()
    {
        $this->flushLogsOnDays();
        $this->rollOverLogs();
    }
}