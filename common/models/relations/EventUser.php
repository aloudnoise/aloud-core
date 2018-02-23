<?php

namespace common\models\relations;

use common\components\ActiveRecord;
use common\models\Events;
use common\models\notifications\UserNotification;
use common\models\Users;

class EventUser extends RelationsTemplate
{

    const STATE_FINISHED = 2;

    public function attributesToInfo()
    {
        return ['criteria'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['criteria'], 'safe']
        ]);
    }

    public static function tableName()
    {
        return "relations.event_user";
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'related_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {

        $notification = new UserNotification();
        $notification->target_type = UserNotification::TARGET_USER;
        $notification->target_id = $this->related_id;
        $notification->name = $this->event->name;
        $notification->content = 'Вас прикреприли к мероприятию';
        $notification->icon = 'calendar';
        $notification->color = 'success';
        $notification->channels = '00001';
        $notification->save();

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {

        $notification = new UserNotification();
        $notification->target_type = UserNotification::TARGET_USER;
        $notification->target_id = $this->related_id;
        $notification->name = $this->event->name;
        $notification->content = 'Вас исключили из мероприятия';
        $notification->icon = 'calendar';
        $notification->color = 'danger';
        $notification->channels = '00001';
        $notification->save();

        parent::afterDelete();
    }

}

?>