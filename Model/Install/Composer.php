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
use Symfony\Component\Process\Process;

class Composer
{
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
    private $deploy;

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
        Deploy        $deploy
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
    public function install($package)
    {
        $this->installComposer($package);
        $this->deploy->run();
    }

    /**
     * @param $package
     * @return string|void
     */
    public function installComposer($package)
    {
        $rootDirectoryPath = $this->dir->getRoot();
        try {
            $process = new Process(['composer', 'require', $package]);
            $process->setWorkingDirectory($rootDirectoryPath);
            $process->setEnv(['COMPOSER_HOME' => $rootDirectoryPath, 'VENDOR_PATH' => $rootDirectoryPath . '/app/etc/vendor_path.php', 'COMPOSER_AUTH' => '{"http-basic":{"repo.magento.com":{"username":"cfbd0337f043f2f245f4f94990871085","password":"4e933d0c43d4ad432363bdfc181f24c6"}}}']);
            $process->setTimeout(9999999);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->logger->info($process->getErrorOutput());
                return $process->getErrorOutput();
            }
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            return $e->getMessage();
        }
    }
}
