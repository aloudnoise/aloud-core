<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 03.01.2018
 * Time: 21:52
 */

namespace app\models;


use app\traits\BackboneRequestTrait;

class Messages extends \common\models\Messages
{

    public static function getMessagesHash($user_id)
    {
        return md5('messages_socket_stream_'.$user_id);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'chat_id' => 'Chat ID',
            'user_id' => 'User ID',
            'message' => 'Message',
            'ts' => 'Ts',
            'info' => 'Info',
        ];
    }

}