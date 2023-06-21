<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Model\Install;

use Im\Installer\Logger\Logger;
use Im\Installer\Model\Deploy;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use ZipArchive;

class Archive
{
    const INSTANCE = 'var/installer/';

    /**
     * @var DirectoryList
     */
    private $dir;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Deploy
     */
    private  $deploy;

    /**
     * @param DirectoryList $dir
     * @param File $file
     * @param Logger $logger
     * @param Filesystem $filesystem
     * @param Deploy $deploy
     */
    public function __construct(
        DirectoryList $dir,
        File          $file,
        Logger        $logger,
        Filesystem    $filesystem,
        Deploy $deploy
    )
    {
        $this->dir = $dir;
        $this->file = $file;
        $this->logger = $logger;
        $this->filesystem = $filesystem;
        $this->deploy = $deploy;
    }

    /**
     * @param $module
     * @return void
     */
    public function install($module)
    {
        $this->installArchive($module);
        $this->deploy->run();
    }

    /**
     * @param $module
     * @return bool
     */
    public function installArchive($module)
    {
        try {
            if ($zip = $this->getZipArchive($module)) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if ($filename && false !== strpos($filename, 'module.xml')) {
                        $stat = $zip->getFromName($filename);

                        $xml = simplexml_load_string($stat);
                        $moduleName = (string)$xml->module['name'];
                        $moduleNameWithVendor = explode('_', $moduleName);
                        $codeDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::APP);

                        if (!$codeDirectory->isDirectory('/code/' . $moduleNameWithVendor[0])) {
                            $codeDirectory->create('/code/' . $moduleNameWithVendor[0]);
                        }

                        $zip->extractTo($codeDirectory->getAbsolutePath() . '/code/' . $moduleNameWithVendor[0]);
                        $zip->close();

                        return true;
                    }
                }
            }
            return false;

        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            return false;
        }
    }


    /**
     * @param $path
     * @return false|ZipArchive
     */
    public function getZipArchive($path)
    {
        $zip = new ZipArchive;

        return $zip->open($path) ? $zip : false;
    }
}
