<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Model;

use Im\Installer\Logger\Logger;
use Magento\Framework\App\MaintenanceMode;

class Deploy
{

    /**
     * @var MaintenanceMode
     */
    private $maintenanceMode;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param MaintenanceMode $maintenanceMode
     * @param Logger $logger
     */
    public function __construct(
        MaintenanceMode $maintenanceMode,
        Logger $logger
    ) {
        $this->maintenanceMode = $maintenanceMode;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->maintenanceMode->set(true);

        foreach (['setup:upgrade', 'setup:di:compile'] as $commands) {
            $k[0] = 'bin/magento';
            $k[1] = $commands;
            $_SERVER['argv'] = $k;
            try {
                $handler = new \Magento\Framework\App\ErrorHandler();
                set_error_handler([$handler, 'handler']);
                $application = new \Magento\Framework\Console\Cli('Install Module');
                $application->run();
            } catch (\Exception $e) {
                $this->logger->info($e->getMessage());
            }
        }
        $this->maintenanceMode->set(false);
    }
}
