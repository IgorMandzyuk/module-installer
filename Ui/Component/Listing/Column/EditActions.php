<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Im\Installer\Ui\Component\Listing\Column;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;

/**
 * Class GroupActions
 *
 * Customer Groups actions column
 */
class EditActions extends Column
{
    /**
     * Url path
     */
    const URL_PATH_EDIT = 'im_installer/installer/form';

    /**
     * @var GroupManagementInterface
     */
    private $groupManagement;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     * @param GroupManagementInterface|null $groupManagement
     */
    public function __construct(
        ContextInterface         $context,
        UiComponentFactory       $uiComponentFactory,
        UrlInterface             $urlBuilder,
        Escaper                  $escaper,
        array                    $components = [],
        array                    $data = [],
        GroupManagementInterface $groupManagement = null
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->escaper = $escaper;
        $this->groupManagement = $groupManagement ?: ObjectManager::getInstance()->get(GroupManagementInterface::class);

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['id']
                                ]
                            ),
                            'label' => __('Detail'),
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
