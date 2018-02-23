<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 19.01.2018
 * Time: 0:36
 */

namespace app\models\reports;


use common\models\Materials;
use TinCan\Activity;
use TinCan\Verb;

class ReportDers extends ReportAbstract
{

    public function getLists()
    {
        return [
            'ders' => Materials::find()
                ->byOrganization()->andWhere([
                    'type' => Materials::TYPE_DER,
                ])->andWhere([
                    'OR', [
                        'language' => \Yii::$app->language
                    ],
                    [
                        'language' => null
                    ]
                ])->orderBy("name")->all()
        ];
    }

    public function getData()
    {

        $start = (new \DateTime($this->filter->start))->getTimestamp();
        $end = strtotime($this->filter->end) + 86399;

        $query['since'] = $start;
        $query['until'] = $end;
        $query['ascending'] = true;

        $query = [
            "limit" => 1000
        ];

        if ($this->filter->custom['der_activity_id']) {
            $course = new Activity();
            $course->setId($this->filter->custom['der_activity_id']);
            $query['activity'] = $course;
            $query['related_activities'] = true;
        }

        $statements = \Yii::$app->tincan->get($query);


        return $statements;

    }

}