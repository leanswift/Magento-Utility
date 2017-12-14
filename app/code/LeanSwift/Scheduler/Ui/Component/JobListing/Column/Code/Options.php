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
namespace LeanSwift\Scheduler\Ui\Component\JobListing\Column\Code;

/**
 * Define the options available for the column "code" in the jobs listing
 * @version 1.0.0
 */
class Options implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @var array 
     */
    protected $options = null;

    /**
     * @var \Magento\Cron\Model\ConfigInterface 
     */
    public $cronConfig = null;

    /**
     * Class constructor
     * @param \Magento\Cron\Model\ConfigInterface $cronConfig
     */
    public function __construct(
    \Magento\Cron\Model\ConfigInterface $cronConfig
    )
    {
        $this->cronConfig = $cronConfig;
    }

    /**
     * Get all options available
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        
        if ($this->options === null) {
            $configJobs = $this->cronConfig->getJobs();
            foreach (array_values($configJobs) as $jobs) {
                foreach (array_keys($jobs) as $code) {
                    $options[] = $code;
                }
            }
        }

        sort($options);
        foreach ($options as $option) {
            $this->options[] = [
                "label" => $option, "value" => $option
            ];
        }
        return $this->options;
    }

}
