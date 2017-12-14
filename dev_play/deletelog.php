<?php

// Magento server root directory.
define('MAGENTO', $_SERVER["DOCUMENT_ROOT"]);

// Magento Log file path.
define('LOG_PATH', '/var/log/');

$directory = MAGENTO . LOG_PATH;

// check directory is exist.
if (is_dir($directory)) {
    if ($opendir = opendir($directory)) {

        while (($fileName = readdir($opendir)) !== false) {

            if ($fileName == '.' || $fileName == '..' || $fileName == '.htaccess') {
                continue;
            }

            $filePath = $directory . $fileName;

            // check file is exist.
            if (file_exists($filePath)) {

                // Remove the file from folder.
                if (!unlink($filePath)) {
                    echo "Error deleting the file = ". $fileName;
                    echo "<br />";
                } else {
                    echo "File Deleted = ". $fileName;
                    echo "<br />";
                }
            }
        }

        closedir($dh);
    }
}
?>