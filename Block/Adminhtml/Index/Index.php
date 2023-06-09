<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Im\Installer\Block\Adminhtml\Index;

use Magento\Backend\Block\Template\Context;

class Index extends \Magento\Backend\Block\Template
{

    /**
     * Constructor
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array   $data = []
    ) {
        parent::__construct($context, $data);
    }
}
