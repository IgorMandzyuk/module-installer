<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Controller\Adminhtml\Module\Install;

use Magento\Backend\App\Action\Context;
use Im\Installer\Logger\Logger;
use Magento\Framework\Message\Manager;

abstract class AbstractInstaller extends \Magento\Backend\App\Action
{

    /**
     * @var Manager
     */
    protected $massageManager;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Context $context
     * @param Manager $massageManager
     * @param Logger $logger
     */
    public function __construct(
        Context          $context,
        Manager          $massageManager,
        Logger $logger
    ) {
        parent::__construct($context);
        $this->massageManager = $massageManager;
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    abstract public function execute();
}
