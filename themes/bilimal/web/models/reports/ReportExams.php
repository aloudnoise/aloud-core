<?php
namespace bilimal\web\models\reports;

use app\models\reports\ReportAbstract;
use app\models\results\TestResults;

class ReportExams extends ReportAbstract
{

    public function getLists()
    {
        return parent::getLists();
    }

    public function getData()
    {

        $start = (new \DateTime($this->filter->start))->format('d.m.Y H:i:s');
        $end = date('d.m.Y H:i:s', strtotime($this->filter->end) + 86399);

        $testResults = TestResults::find()
            ->joinWith([
                'lesson.course',
                'event'
            ])
            ->byOrganization()
            ->with([
                "test",
                "user"
            ])
            ->andWhere("test_results.finished IS NOT NULL")
            ->andWhere("test_results.from != 'practice' AND test_results.from != 'testing'")
            ->andWhere([
                "between", "finished", $start, $end
            ])
            ->orderBy("test_results.ts DESC");

        $this->applyCustom($testResults);
        $this->applyCustomResult($testResults);

        return $testResults->all();

    }

}