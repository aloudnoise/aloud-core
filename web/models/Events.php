<?php
namespace app\models;


use app\traits\BackboneRequestTrait;

class Events extends \common\models\Events
{

    use BackboneRequestTrait;

    public static function getCurrentEvents()
    {
        if (!\Yii::$app->has("current_events")) {
            $events = Events::find()
                ->current()
                ->byOrganization()
                ->joinWith([
                    "member"
                ])
                ->andWhere("(event_user.id IS NOT NULL OR events.state = :s)", [
                    ":s" => Events::STATE_SHARED
                ])
                ->orderBy('events.begin_ts DESC')
                ->all();
            \Yii::$app->set("current_events", function() use ($events) {
                return $events;
            });
        }
        return \Yii::$app->get("current_events");
    }

    public function deleteAccess($attributes = [])
    {
        return true;
    }

    public function deleteRequest($attributes = []) {
        $c = Events::find()->byPk($attributes['id'])->byOrganization()->one();
        if ($c->delete()) {

            return true;
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t('main', 'Наименование'),
            'begin_ts' => \Yii::t('main', 'Дата начала'),
            'begin_ts_display' => \Yii::t('main', 'Дата начала'),
            'end_ts' => \Yii::t('main', 'Дата окончания'),
            'end_ts_display' => \Yii::t('main', 'Дата окончания'),
            'state' => 'State',
            'ts' => \Yii::t('main', 'Дата создания'),
            'period' => \Yii::t('main', 'Период'),
            'tagsString' => \Yii::t("main","Ключевые слова")
        ];
    }

}