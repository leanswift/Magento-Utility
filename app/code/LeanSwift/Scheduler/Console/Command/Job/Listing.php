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

namespace LeanSwift\Scheduler\Console\Command\Job;

class Listing extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var \Magento\Cron\Model\ConfigInterface
     */
    protected $_cronConfig = null;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $_state = null;

    /**
     * Class constructor
     * @param \Magento\Cron\Model\ConfigInterface $cronConfig
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
    \Magento\Cron\Model\ConfigInterface $cronConfig,
            \Magento\Framework\App\State $state
    )
    {
        $this->_state = $state;
        $this->_cronConfig = $cronConfig;
        parent::__construct();
    }

    /**
     * Configure the command line
     */
    protected function configure()
    {
        $this->setName('leanswift:scheduler:job:list')
                ->setDescription(__('Cron Scheduler : get list of all jobs'))
                ->setDefinition([]);
        parent::configure();
    }

    /**
     * Execute the command line
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int \Magento\Framework\Console\Cli::RETURN_FAILURE or \Magento\Framework\Console\Cli::RETURN_SUCCESS
     */
    protected function execute(
    \Symfony\Component\Console\Input\InputInterface $input,
            \Symfony\Component\Console\Output\OutputInterface $output
    )
    {


        try {
            $this->_state->setAreaCode('adminhtml');

            $configJobs = $this->_cronConfig->getJobs();


            $data = [];
            $max = [0, 0, 0, 0];

            foreach ($configJobs as $group => $jobs) {
                foreach ($jobs as $code => $job) {
                    $instance = $job['instance'];
                    $method = $job['method'];
                    $schedule = (isset($job['schedule']) ? $job['schedule'] : "");
                    $itemData = [
                        'code' => $code,
                        'instance' => $instance . "::" . $method,
                        'schedule' => $schedule,
                        'group' => $group,
                    ];
                    $max = [
                        max(strlen($itemData['code']), $max[0]),
                        max(strlen($itemData['group']), $max[1]),
                        max(strlen($itemData['instance']), $max[2]),
                        max(strlen($itemData['schedule']), $max[3]),
                    ];
                    $data[] = $itemData;
                }
            }

            sort($data);

            $output->writeln("");

            $row = sprintf(" %-" . $max[0] . "s | %-" . $max[1] . "s | %-" . $max[2] . "s | %-" . $max[3] . "s ", __("Code"), __("Group"), __("Method"), __("Schedule"));
            $output->writeln($row);
            $separator = sprintf("-%'-" . $max[0] . "s-+-%'-" . $max[1] . "s-+-%'-" . $max[2] . "s-+-%'-" . $max[3] . "s", "", "", "", "");
            $output->writeln($separator);

            $counter = 0;
            $count = count($data);
            foreach ($data as $item) {
                $counter++;
                $row = sprintf(" %-" . $max[0] . "s | %-" . $max[1] . "s | %-" . $max[2] . "s | %-" . $max[3] . "s ", $item['code'], $item['group'], $item['instance'], $item['schedule']);
                $output->writeln($row);
                if ($count !== $counter) {
                    $output->writeln($separator);
                }
            }
            $returnValue = \Magento\Framework\Console\Cli::RETURN_SUCCESS;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $output->writeln($e->getMessage());
            $returnValue = \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }


        return $returnValue;
    }

}
