<?php
namespace bilimal\web\models\reports;

class ReportsFilter extends \app\models\reports\ReportsFilter
{
    public function getReports()
    {
        return array_merge(parent::getReports(), [
            'exams' => [
                'name' => 'exams',
                'label' => \Yii::t("main","Ведомости"),
                'class' => 'bilimal\web\models\reports\ReportExams'
            ]
        ]);
    }
}