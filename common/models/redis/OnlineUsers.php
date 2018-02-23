<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 18.02.2018
 * Time: 0:23
 */

namespace common\models\redis;


use common\models\Organizations;
use yii\redis\ActiveRecord;

/**
 * Class OnlineUsers
 * @package common\models\redis
 *
 * @property integer $user_id;
 * @property integer $organization_id;
 * @property integer $ts;
 * @property string $role;
 *
 */
class OnlineUsers extends ActiveRecord
{

    public function attributes()
    {
        return [
            'id', 'user_id', 'role', 'organization_id', 'ts'
        ];
    }

    public function beforeSave($insert)
    {

        $this->user_id = \Yii::$app->user->id;
        $this->organization_id = Organizations::getCurrentOrganizationId();
        $this->ts = time();
        $this->role = \Yii::$app->user->identity->currentOrganizationRole;
        return parent::beforeSave($insert);
    }

    public static function setOnline()
    {
        if (Organizations::getCurrentOrganizationId() AND !\Yii::$app->user->isGuest) {
            $online = static::find()->andWhere([
                'organization_id' => Organizations::getCurrentOrganizationId(),
                'user_id' => \Yii::$app->user->id
            ])->one();

            if (!$online) {
                $online = new static();
            }
            $online->save();
        }

    }

}