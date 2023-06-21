<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Block\Adminhtml\Installer\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var Registry
     */
    private $_coreRegistry;

    /**
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context          $context,
        EncoderInterface $jsonEncoder,
        Session          $authSession,
        Registry         $coreRegistry,
        array            $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDestElementId('install_form');
        $this->setTitle(__('Install module by'));
    }

    /**
     * @return Tabs
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'status',
            [
                'label' => __('Archive'),
                'content' => $this->getLayout()->createBlock(
                    'Im\Installer\Block\Adminhtml\Installer\Edit\Tab\Archive'
                )->toHtml(),
                'active' => true
            ]
        );
        $this->addTab(
            'main',
            [
                'label' => __('Composer'),
                'content' => $this->getLayout()->createBlock(
                    'Im\Installer\Block\Adminhtml\Installer\Edit\Tab\Composer'
                )->toHtml()
            ]
        );
        return parent::_prepareLayout();
    }
}
