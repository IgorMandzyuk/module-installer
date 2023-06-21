<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Controller\Adminhtml\Module\Install;

use Im\Installer\Logger\Logger;
use Im\Installer\Model\Install\Archive as ArchiveInstaller;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList as DirList;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Message\ManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Message\Manager;

class Archive extends AbstractInstaller
{
    const ADMIN_RESOURCE = 'Im_Installer::index_index';

    /**
     * @var DirectoryList
     */
    private $dir;

    /**
     * @var UploaderFactory
     */
    private $fileUploaderFactory;

    /**
     * @var ArchiveInstaller
     */
    private $archiveInstaller;

    /**
     * @param UploaderFactory $fileUploaderFactory
     * @param DirectoryList $dir
     * @param ArchiveInstaller $archiveInstaller
     * @param Context $context
     * @param Manager $massageManager
     * @param Logger $logger
     */
    public function __construct(
        UploaderFactory  $fileUploaderFactory,
        DirectoryList    $dir,
        ArchiveInstaller $archiveInstaller,
        Context          $context,
        Manager          $massageManager,
        Logger           $logger
    )
    {
        parent::__construct($context, $massageManager, $logger);
        $this->archiveInstaller = $archiveInstaller;
        $this->dir = $dir;
        $this->fileUploaderFactory = $fileUploaderFactory;
    }

    /**
     * @return ManagerInterface|void
     */
    public function execute()
    {
        $importRatesFile = $this->getRequest()->getFiles('file');
        if ($this->getRequest()->isPost() && isset($importRatesFile['tmp_name'])) {
            try {
                $target = $this->dir->getPath(DirList::VAR_DIR) . '/installer_modules';
                $uploader = $this->fileUploaderFactory->create(['fileId' => 'file']);
                $uploader->setAllowedExtensions(['zip']);
                $uploader->setAllowRenameFiles(true);
                $result = $uploader->save($target);
                $this->archiveInstaller->install($result['path'] . '/' . $result['file']);
                if ($result['file']) {
                    $this->archiveInstaller->install($result['file']);
                    return $this->messageManager->addSuccessMessage('Module successfully installed');
                }
            } catch (\Exception $e) {
                $this->logger->info($e->getMessage());
                $this->messageManager->addSuccessMessage('Error when installing module: ' . $e->getMessage());
            }
        } else {
            return $this->messageManager->addErrorMessage('Error file not found');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
