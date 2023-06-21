<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /**
     * @var null
     */
    protected $storeId = null;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setStoreId($id)
    {
        $this->storeId = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogoUrl()
    {
        return $this->config('maintenance/logo');
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return (string)$this->config('general/background_color');
    }

    /**
     * @return string
     */
    public function getSideColor()
    {
        return (string)$this->config('general/side_background_color');
    }

    /**
     * @return string
     */
    public function getFooterAndHeaderColor()
    {
        return (string)$this->config('general/footer_and_header_background_color');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return (string)$this->config('maintenance/title');
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return (string)$this->config('maintenance/copyright');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->config('maintenance/description');
    }

    protected function config($code)
    {
        return $this->scopeConfig->getValue("iminstaller/{$code}", ScopeInterface::SCOPE_STORE, $this->storeId);
    }
}
