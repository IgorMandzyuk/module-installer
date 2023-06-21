<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Block\Adminhtml\Installer;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_blockGroup = 'Im_Installer';
        $this->_controller = 'adminhtml_installer';
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('save');
        $this->buttonList->add(
            'install',
            [
                'label' => __('Install'),
                'class' => 'primary'
            ]
        );

        $this->buttonList->update('back', 'onclick', 'setLocation(\'' . $this->getBackUrl() . '\')');
        $this->buttonList->update('install', 'id', 'upload_button');
        $this->buttonList->update('install', 'onclick', 'varienImport.startInstallation();');
        $this->buttonList->update('install', 'data_attribute', '');
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('im_installer/module/grid/');
    }
}
