<?php

/**
 * Magento Scheduler  by LeanSwift
 *
 * NOTICE OF LICENSE
 *  This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, Version 3 of the License. You can view
 *   the license here http://opensource.org/licenses/GPL-3.0

 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 * @category    LeanSwift
 * @package     LeanSwift_Scheduler
 * @copyright   Copyright (c) 2017 LeanSwift (http://www.leanswift.com)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 *
 */

namespace LeanSwift\Scheduler\Ui\DataProvider;

/**
 * Job provider for the jobs listing
 * @version 1.0.0
 */
class JobProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /**
     * @var integer
     */
    protected $_size = 20;

    /**
     * @var integer
     */
    protected $_offset = 1;

    /**
     * @var array
     */
    protected $_likeFilters = [];

    /**
     * @var array
     */
    protected $_rangeFilters = [];

    /**
     * @var string
     */
    protected $_sortField = 'code';

    /**
     * @var string
     */
    protected $_sortDir = 'asc';

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $_directoryList = null;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    private $_directoryRead = null;

    /**
     * @var \LeanSwift\Scheduler\Helper\Job
     */
    public $jobHelper = null;

    /**
     * Class constructor
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $directoryRead
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Cron\Model\ConfigInterface $jobHelper
     * @param array $meta
     * @param array $data
     */
    public function __construct(
    $name,
            $primaryFieldName,
            $requestFieldName,
            \Magento\Framework\Filesystem\Directory\ReadFactory $directoryRead,
            \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
            \LeanSwift\Scheduler\Helper\Job $jobHelper,
            array $meta = [],
            array $data = []
    )
    {
        $this->_directoryRead = $directoryRead;
        $this->_directoryList = $directoryList;
        $this->jobHelper = $jobHelper;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Set the limit of the collection
     * @param type $offset
     * @param type $size
     */
    public function setLimit(
    $offset,
            $size
    )
    {
        $this->_size = $size;
        $this->_offset = $offset;
    }

    /**
     * Get the collection
     * @return type
     */
    public function getData()
    {

        $data = array_values($this->jobHelper->getJobData());

        
        $totalRecords = count($data);

        // sorting
        $sortField = $this->_sortField;
        $sortDir = $this->_sortDir;
        usort($data, function($a, $b) use ($sortField, $sortDir) {
            if ($sortDir == "asc") {
                return $a[$sortField] > $b[$sortField];
            } else {
                return $a[$sortField] < $b[$sortField];
            }
        });

        // filters
        foreach ($this->_likeFilters as $column => $value) {
            $data = array_filter($data, function($item) use ($column, $value) {
                return stripos($item[$column], $value) !== false;
            });
        }

        // pagination
        $data = array_slice($data, ($this->_offset - 1) * $this->_size, $this->_size);

        return [
            'totalRecords' => $totalRecords,
            'items' => $data,
        ];
    }

    /**
     * Add filters to the collection
     * @param \Magento\Framework\Api\Filter $filter
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getConditionType() == "like") {
            $this->_likeFilters[$filter->getField()] = substr($filter->getValue(), 1, -1);
        } elseif ($filter->getConditionType() == "eq") {
            $this->_likeFilters[$filter->getField()] = $filter->getValue();
        } elseif ($filter->getConditionType() == "gteq") {
            $this->_rangeFilters[$filter->getField()]['from'] = $filter->getValue();
        } elseif ($filter->getConditionType() == "lteq") {
            $this->_rangeFilters[$filter->getField()]['to'] = $filter->getValue();
        }
    }

    /**
     * Set the order of the collection
     * @param type $field
     * @param type $direction
     */
    public function addOrder(
    $field,
            $direction
    )
    {
        $this->_sortField = $field;
        $this->_sortDir = strtolower($direction);
    }

}
