<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Block\Adminhtml\Installer\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

class Composer extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Context     $context,
        Registry    $registry,
        FormFactory $formFactory,
        array       $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Composer
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset(
            'composer_tab',
            ['legend' => __('Install via Composer'), 'class' => 'composer']
        );

        $fieldset->addField(
            'package',
            'text',
            [
                'name' => 'package',
                'label' => __('Package Name'),
                'id' => 'title',
                'title' => __('Package Name'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
