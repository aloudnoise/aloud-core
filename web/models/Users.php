<?php

namespace app\models;

use api\modules\users\models\Auth;
use app\traits\BackboneRequestTrait;
use common\models\AuthCredentials;
use common\models\bilimal\LinksGenerator;
use common\models\bilimal\PersonCredential;
use common\models\Notifications;
use common\models\relations\ChatUser;
use common\traits\UpdateInsteadOfDeleteTrait;


class Users extends \common\models\Users
{
    use BackboneRequestTrait;
    use UpdateInsteadOfDeleteTrait;

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return Users|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function backboneArray()
    {

        return array(
            'isGuest' => \Yii::$app->user->isGuest,
            'id' => \Yii::$app->user->id,
            'model' => \Yii::$app->user->isGuest ? null : self::arrayAttributes($this, [], ["id"]),
            'token' => $this->getTokenCredential(),
            'messages_hash' => Messages::getMessagesHash($this->id),
            'notifications_hash' => Notifications::getNotificationHash($this->id)
        );

    }

    public function getTokenCredential()
    {
        if (!\Yii::$app->user->isGuest) {
            $credentials = $this->credentials;
            if ($credentials['token']) {
                return $credentials['token']->credential;
            }

            $auth = new Auth();
            $token = $auth->generateAuthToken(\Yii::$app->user->id);
            return $token->credential;

        }
        return null;
    }

    public function getHash()
    {
        return md5($this->id.\Yii::$app->params['secret_word']);
    }

}