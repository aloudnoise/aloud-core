<?php
namespace api\models;

use common\models\AuthCredentials;

/**
 * Class User
 *
 * @package api\models
 * @property string $guid Уникальное значение для временной регистрации через мобильное приложение
 */
class Users extends \common\models\Users
{
    /**
     * Добавляем в набор правил доп. правило для временной регистрации
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            [['guid'], 'string', 'max'=>50],
            [['guid'], 'unique'],
            [['guid'], 'required', 'on' => ['temporary']],
        ], parent::rules());
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param null $type
     * @return ActiveRecord|array|bool|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($token) {
            $u = AuthCredentials::findOne([
                'credential' => $token,
                'type' => 'token'
            ]);

            if ($token && $u->user_id) {
                $user = self::find()->byPk($u->user_id)->one();
                if ($user) {
                    $user->authorization_token = $u->credential;
                    return $user;
                }
                return false;
            }
        }

        return false;
    }
}