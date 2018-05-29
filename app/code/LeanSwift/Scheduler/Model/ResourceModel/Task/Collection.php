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

namespace LeanSwift\Scheduler\Model\ResourceModel\Task;


class Collection extends \Magento\Cron\Model\ResourceModel\Schedule\Collection
{

    protected $_idFieldName = "schedule_id";

    public function sortByScheduledAtDesc() {
        $this->getSelect()->order('scheduled_at DESC');
        return $this;
    }
    

    public function getJobCodes()
    {
        $this->getSelect()->reset('columns')
                ->columns('DISTINCT(job_code) as job_code')
                ->order('job_code ASC');

        return $this;
    }


    public function getTaskStatuses()
    {
        $this->getSelect()->reset('columns')
                ->columns('DISTINCT(status) as status')
                ->order('status ASC');

        return $this;
    }


    public function getLastHeartbeat()
    {
        $this->getSelect()->reset('columns')
                ->columns(['executed_at'])
                ->where('executed_at is not null and job_code ="leanswift_scheduler_check"')
                ->order('finished_at desc');

        $last = $this->getFirstItem();
        if ($last) {
            return $last->getExecutedAt();
        } else {
            return null;
        }
    }

}
