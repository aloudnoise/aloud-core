<?php
namespace api\models\results;

class TaskResults extends \common\models\results\TaskResults
{
    public function attributesForSave($scenario)
    {
        return ['result', 'reviewer_id', 'review_ts'];
    }

    public function rules()
    {
        return [
            [['reviewer_id'], 'default', 'value' => \Yii::$app->user->id],
            [['review_ts'], 'default', 'value' => time()],
            [['result'], 'string', 'max' => 2000],
        ];
    }
}