<?php
namespace krw\web\models\reports;

class ReportsFilter extends \app\models\reports\ReportsFilter
{
    public function getReports()
    {
        return array_merge(parent::getReports(), [
            'ders' => [
                'name' => 'ders',
                'label' => \Yii::t("main","Статистика по ЦОР")
            ]
        ]);
    }
}