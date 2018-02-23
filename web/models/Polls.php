<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.01.2018
 * Time: 23:21
 */

namespace app\models;


class Polls extends \common\models\Polls
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t("main","Название голосования"),
            'user_id' => 'User ID',
            'organization_id' => 'Organization ID',
            'info' => 'Info',
            'ts' => 'Ts',
            'is_deleted' => 'Is Deleted',
            'status' => 'Status',
        ];
    }

    public static function getActiveList()
    {
        return static::find()->joinWith([
            'myResult'
        ])->byOrganization()->andWhere([
            'polls.status' => static::STATUS_ACTIVE,
            'poll_results.id' => null
        ])->orderBy('polls.ts DESC')->all();
    }

}