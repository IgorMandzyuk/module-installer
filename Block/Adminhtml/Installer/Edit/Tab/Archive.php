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

class Archive extends \Magento\Backend\Block\Widget\Form\Generic
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
     * @return Archive
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset(
            'archive_tab',
            ['legend' => __('Install by archive'), 'class' => 'archive']
        );

        $fieldset->addField(
            'archive',
            'file',
            [
                'name' => 'archive',
                'label' => __('Archive module'),
                'title' => __('Archive module'),
                'required' => true,
                'class' => 'input-file',
                'note' => __(
                    'File must be saved in UTF-8 encoding for proper import'
                ),
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
