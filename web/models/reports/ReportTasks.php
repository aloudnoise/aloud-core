<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 16.01.2018
 * Time: 19:31
 */

namespace app\models\reports;


use app\models\Events;
use app\models\results\TaskResults;

class ReportTasks extends ReportAbstract
{
    public function getLists()
    {
        return parent::getLists();
    }

    public function getData()
    {

        $start = (new \DateTime($this->filter->start))->format('d.m.Y H:i:s');
        $end = date('d.m.Y H:i:s', strtotime($this->filter->end) + 86399);

        $taskResults = TaskResults::findAllStatuses()
            ->byOrganization()
            ->with([
                "task",
                "user"
            ])
            ->andWhere("task_results.finished IS NOT NULL AND task_results.result IS NOT NULL")
            ->andWhere([
                "between", "finished", $start, $end
            ])
            ->orderBy("task_results.ts DESC");

        $this->applyCustom($taskResults);
        $this->applyCustomResult($taskResults);

        return $taskResults->all();

    }
}