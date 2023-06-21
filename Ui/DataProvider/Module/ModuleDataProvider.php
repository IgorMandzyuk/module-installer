<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Im\Installer\Ui\DataProvider\Module;

use Magento\Framework\Api\Filter;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\Module\Manager;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\App\Request\Http;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class ModuleDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var
     */
    private $offset;

    /**
     * @var
     */
    private $size;

    /**
     * @var AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * @var ModuleList
     */
    private ModuleList $moduleList;

    /**
     * @var FullModuleList
     */
    private FullModuleList $fullModuleList;

    /**
     * @var ModuleListInterface
     */
    private ModuleListInterface $moduleListInterface;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Manager $moduleManager
     * @param FullModuleList $fullModuleList
     * @param ModuleListInterface $moduleListInterface
     * @param Http $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Manager $moduleManager,
        \Magento\Framework\Module\FullModuleList $fullModuleList,
        ModuleListInterface $moduleListInterface,
        Http $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->moduleManager = $moduleManager;
        $this->fullModuleList = $fullModuleList;
        $this->moduleListInterface = $moduleListInterface;
        $this->request = $request;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        $items = [];
        $id = 1;
        foreach ($this->fullModuleList->getAll() as $key => $value) {
            $detailModuleInfo = $this->moduleListInterface->getOne($value['name']);
            $value['id'] = $id;
            $value['status'] = $this->moduleManager->isEnabled($value['name']) ? 'Enabled' : 'Disabled';
            $value['sequence'] = isset($detailModuleInfo['sequence']) ? implode(',<br>', $detailModuleInfo['sequence']) : 'Noone sequence';
            $value['setup'] = $detailModuleInfo['setup_version'] ?? 'None';
            $items[$id] = $value;
            $id++;
        }

        $pageOffset = ($this->offset - 1) * $this->size;

        return [
            'totalRecords' => count($items),
            'items' => array_slice($items, $pageOffset, $pageOffset + $this->size),
        ];
    }

    /**
     * @param $offset
     * @param $size
     * @return void
     */
    public function setLimit($offset, $size): void
    {
        $this->offset = $offset;
        $this->size = $size;
    }

    /**
     * @param $field
     * @param $direction
     * @return void
     */
    public function addOrder($field, $direction): void
    {
    }

    /**
     * @param Filter $filter
     * @return void
     */
    public function addFilter(Filter $filter): void
    {
    }
}
