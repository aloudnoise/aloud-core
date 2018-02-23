<?php

namespace common\models;

use common\models\notifications\BaseNotification;
use common\models\notifications\ConferenceNotification;
use common\models\notifications\EventNotification;
use common\models\notifications\TaskNotification;
use common\traits\AttributesToInfoTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $target_id
 * @property string $target_type
 * @property int $type
 * @property string $channels
 * @property string $info
 * @property string $ts
 * @property int $is_deleted
 */
class Notifications extends \common\components\ActiveRecord
{

    use AttributesToInfoTrait, UpdateInsteadOfDeleteTrait;

    // channels = 0/1 - sms, 0/1 - push, 0/1 - email, 0/1 - web 0/1 - socket
    // example = 00111 ( -sms, -push, +email, +web +socket)

    const TYPE_BASE = 1;
    const TYPE_EVENT = 2;
    const TYPE_USER = 3;
    const TYPE_TASK = 100;
    const TYPE_CONFERENCE = 105;


    const TARGET_USER = 'user';
    const TARGET_ALL = 'all';
    const TARGET_PUPILS = 'pupils';
    const TARGET_TEACHERS = 'teachers';

    const TARGET_EVENT = 'event';

    const CHANNEL_SMS = 0;
    const CHANNEL_PUSH = 1;
    const CHANNEL_EMAIL = 2;
    const CHANNEL_WEB = 3;
    const CHANNEL_SOCKET = 4;

    public static function instantiate($row)
    {
        $models = [
            static::TYPE_BASE => BaseNotification::className(),
            static::TYPE_CONFERENCE => ConferenceNotification::className(),
            static::TYPE_TASK => TaskNotification::className(),
            static::TYPE_EVENT => EventNotification::className()
        ];

        return new $models[$row['type']]();

    }

    public function channel($channel)
    {
        return substr($this->channels, $channel, 1);
    }

    public static function getNotificationHash($user_id)
    {
        return md5('notifications_socket_stream_'.$user_id);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channels'], 'safe'],
            [['channels'], 'filter', 'filter' => function($value) {
                return $value ?: '00001';
            }],
            [['organization_id', 'target_id'], 'integer'],
            [['target_type'], 'string', 'max' => 250],
            [['channels'], 'string', 'max' => 4],
            [['info'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'channels' => 'Channels',
            'info' => 'Info',
            'ts' => 'Ts',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            return false;
        }
        /* @var $rabbit \common\components\Rabbit */
        $rabbit = \Yii::$app->rabbit;
        $rabbit->addToQueue($this);
        return true;
    }

    public function socket($online_users)
    {
        if ($online_users) {
            $json = json_encode($this->toArray());
            foreach ($online_users as $ouser) {
                \Yii::$app->redis->publish(static::getNotificationHash($ouser->user_id), $json);
            }
        }
    }

}
