<?php

namespace app\models\reports;

use app\models\Events;
use app\models\Groups;
use app\models\relations\EventCourse;
use common\components\ActiveQuery;
use common\models\relations\GroupUser;
use yii\db\Expression;

abstract class ReportAbstract implements IReport {

    protected $filter;

    protected $_data = null;

    public function getLists()
    {
        return [
            'teachers' => (\Yii::$container->get('common\models\relations\UserOrganization'))::find()
                ->byOrganization()
                ->with([
                    'user'
                ])
                ->andWhere([
                    '!=', 'role', 'pupil'
                ])->all(),
            'themes' => \common\models\Themes::find()->byOrganization()->orderBy("name")->indexBy("id")->all(),
            'groups' => Groups::find()->byOrganization()->orderBy("name")->all()
        ];
    }

    public function applyCustom(&$query) {

        $start = (new \DateTime($this->filter->start))->format('d.m.Y H:i:s');
        $end = date('d.m.Y H:i:s', strtotime($this->filter->end) + 86399);

        if ($this->filter->custom['teacher_id'] OR $this->filter->custom['theme_id'] OR $this->filter->event) {
            $event_ids = Events::find()
                ->byOrganization()
                ->andWhere("(:ts_start, :ts_end) OVERLAPS (events.begin_ts, events.end_ts)", [
                    ":ts_start" => $start,
                    ":ts_end" => $end
                ])
                ->select(['events.id']);

            if ($this->filter->event) {
                $event_ids->andWhere([
                    'events.id' => $this->filter->event->id
                ]);
            }

            if ($this->filter->custom['teacher_id']) {
                $event_ids->andWhere([
                    'events.user_id' => $this->filter->custom['teacher_id']
                ]);
            }

            if ($this->filter->custom['theme_id']) {
                $event_ids->joinWith([
                    'themes'
                ])->andWhere([
                    'event_theme.related_id' => $this->filter->custom['theme_id']
                ]);
            }

            $event_ids = $event_ids->asArray()->column();

            $lesson_ids = EventCourse::find()
                ->byOrganization()
                ->joinWith([
                    'lessons'
                ])
                ->andWhere([
                    'in', 'target_id', $event_ids
                ])->select(['course_lessons.id'])->asArray()->column();

            $query->andWhere([
                'OR', [
                    'AND', [
                        'in', 'from_id', $lesson_ids
                    ],
                    [
                        'from' => 'lesson'
                    ]
                ],
                [
                    'AND', [
                    'in', 'from_id', $event_ids
                ],
                    [
                        'from' => 'event'
                    ]
                ]
            ]);

        }

        if ($this->filter->custom['group_id']) {

            $member_ids = GroupUser::find()
                ->byOrganization()
                ->andWhere([
                    'target_id' => $this->filter->custom['group_id']
                ])
                ->select(['related_id'])->asArray()->column();

            $query->andWhere([
                'in', 'user_id', $member_ids
            ]);

        }

    }

    public function applyCustomResult(&$query)
    {
        if ($this->filter->custom['result']) {
            $results = explode("-", $this->filter->custom['result']);
            if (count($results) > 1) {
                $min = $results[0];
                $max = $results[1];
                $query->andWhere([
                    'between', 'result', $min, $max
                ]);
            } else {
                $query->andWhere([
                    'result' => $this->filter->custom['result']
                ]);
            }
        }

    }

    /**
     * ReportAbstract constructor.
     *
     * @param ReportsFilter $filter
     */
    public function __construct(ReportsFilter $filter)
    {
        $this->filter = $filter;
    }


}