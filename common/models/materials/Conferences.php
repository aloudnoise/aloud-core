<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 23.01.2018
 * Time: 0:44
 */

namespace common\models\materials;


use common\models\Materials;

class Conferences extends Materials
{

    public function attributesToInfo()
    {
        return ['records', 'is_live', 'live_date', 'live_time', 'is_over', 'moderator_pwd', 'attendee_pwd', 'presentation', 'over_ts', 'welcome'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['is_live'], 'safe'],
            [['is_live'], 'filter', 'filter' => function($value) {
                return $value ? 1 : 0;
            }],
            [['live_date', 'live_time'], 'string'],
            [['live_date', 'live_time'], 'required', 'when' => function($model) {
                return $model->type == static::TYPE_CONFERENCE;
            }],
            [['is_over'], 'integer'],
            [['presentation'], 'filter', 'filter' => function($value) {
                return !is_array($value) ? json_decode($value, true) : $value;
            }],
            [['over_ts','welcome'], 'safe'],
            [['welcome'], 'string', 'max' => 300]
        ]);
    }

    public function getMaterialInfoString()
    {
        if ($this->is_live) {
            return "<p class='text-muted'><small><i class='text-danger fa fa-circle mr-1'></i>" . \Yii::t("main", "Идет трансляция") . "</small></p>";
        } else if ($this->is_over) {
            return "<p class='text-muted'><small>".\Yii::t("main","Вебинар окончен")."</small></p>";
        } else {
            return "<p class='text-muted'><small>".\Yii::t("main","Вебинар начнется <span class='ml-1 text-warning'>{date}</span>", [
                'date' => \app\widgets\EDisplayDate\EDisplayDate::widget([
                    'formatType' => 2,
                    'time' => $this->live_date." ".$this->live_time
                ])
            ])."</small></p>";
        }
    }

    public function getMeetingId()
    {
        return \Yii::$app->params['host']."_material_".$this->id;
    }

}