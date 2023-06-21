<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Controller\Adminhtml\Module\Install;

use Im\Installer\Model\Install\Composer as ComposerInstaller;
use Magento\Backend\App\Action\Context;
use Im\Installer\Logger\Logger;
use Magento\Framework\Message\Manager;

class Composer extends AbstractInstaller
{
    const ADMIN_RESOURCE = 'Im_Installer::index_index';

    /**
     * @var ComposerInstaller
     */
    private $composerInstaller;

    /**
     * @param ComposerInstaller $composerInstaller
     * @param Context $context
     * @param Manager $massageManager
     */
    public function __construct(
        ComposerInstaller $composerInstaller,
        Context           $context,
        Manager           $massageManager,
        Logger            $logger
    )
    {
        parent::__construct($context, $massageManager, $logger);
        $this->composerInstaller = $composerInstaller;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $package = $this->getRequest()->getParam('package');
        if ($package) {
            try {
                $this->composerInstaller->install($package);
                $this->messageManager->addSuccessMessage('Package successfully installed');
            } catch (\Exception $e) {
                $this->logger->info($e->getMessage());
                $this->messageManager->addSuccessMessage('Error when installing module: ' . $e->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage('Error while installing package');
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
