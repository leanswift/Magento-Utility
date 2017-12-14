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

namespace LeanSwift\Scheduler\Helper;

/**
 * Job Helper
 */
class Job extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Cron\Model\ConfigInterface
     */
    protected $_cronConfig = null;

    /**
     * Class constructor
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Cron\Model\ConfigInterface $cronConfig
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context,
            \Magento\Cron\Model\ConfigInterface $cronConfig
    )
    {
        $this->_cronConfig = $cronConfig;
        parent::__construct($context);
    }

    /**
     * Get the job data
     * (independant method in order to be able to plugin it in the Pro version)
     * @return array
     */
    public function getJobData()
    {
        $data = [];
        $configJobs = $this->_cronConfig->getJobs();
        foreach ($configJobs as $group => $jobs) {
            foreach ($jobs as $code => $job) {
                $job['code'] = $code;
                $job['group'] = $group;
                if (!isset($job['config_schedule'])) {
                    if (isset($job['schedule'])) {
                        $job['config_schedule'] = $job['schedule'];
                    } else {
                        $job['config_schedule'] = "";
                    }
                }
                $data[$code] = $job;
            }
        }
        return $data;
    }

}
