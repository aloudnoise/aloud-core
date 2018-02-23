<?php
namespace app\models\results;

use common\models\Users;
use app\traits\BackboneRequestTrait;

class TaskResults extends \common\models\results\TaskResults
{
    use BackboneRequestTrait;

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('main', 'ID'),
            'user_id' => \Yii::t("main",'Автор'),
            'answer' => \Yii::t("main",'Ответ'),
            'result' => \Yii::t("main",'Оценка'),
            'reviewer_id' => \Yii::t("main",'Проверил'),
            'review_ts' => \Yii::t("main",'Дата проверки'),
            'note' => \Yii::t("main","Пояснение"),
            'status_note' => \Yii::t("main", "Причина")
        ];
    }

    public function updateAccess($attributes = [])
    {
        return \Yii::$app->user->can(Users::ROLE_TEACHER);
    }

    public function updateRequest($attributes = [])
    {
        $r = self::find()->byPk($attributes['id'])->one();

        $r->reviewer_id = \Yii::$app->user->id;
        $r->review_ts = time();
        $r->result = $attributes['result'];

        if ($r->save()) {
            return static::arrayAttributes($r, [], [], true);
        }
        return false;
    }
}