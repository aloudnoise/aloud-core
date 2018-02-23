<?php
namespace api\modules\users\models;

use api\models\TokenAuthorization;
use api\models\User;
use common\components\Model;
use common\models\AuthCredentials;

/**
 * Модель авторизации пользователя
 * Class Auth
 * @package api\models
 */
class Auth extends Model
{

    public $login = null;
    public $password = null;
    public $hash = null;

    public $token = null;
    public $user = null;

    public function rules()
    {
        return [
            [['login','password'], 'safe'],
            [['source'], 'integer'],
            [['hash'],'safe']
        ];
    }

    public function info()
    {
        return [

        ];
    }

    /**
     * Логиним
     * @return bool
     */
    public function save($auth = true)
    {
        \Yii::$app->cache->pause();
        if ($this->validate()) {

            $identity = null;
            if (!empty($this->login) && !empty($this->password)) {
                $identity = AuthCredentials::find()->andWhere('lower(credential) = :login', [
                    ':login' => trim($this->login, ' '),
                ])->one();

                if ($identity) {
                    if (!\Yii::$app->security->validatePassword($this->password, $identity->validation)) {
                        $identity = null;
                    }
                }
            }

            if ($identity) {
                if ($auth) {
                    $authorization = $this->generateAuthToken($identity->id, $this->source);
                    if ($authorization !== false) {
                        $this->token = $authorization->token;
                        $this->user = $identity;
                    } else {
                        $this->addErrors($authorization->getErrors());
                        return false;
                    }
                }
                return true;
            }
            $this->addError('password', 'WRONG_LOGIN_PASSWORD');
            return false;
        }
        return false;
    }

    /**
     * Разлогиниваем
     * @return bool
     */
    public function delete($token = null)
    {
        if (\Yii::$app->user->id) {

            // TODO УСТАРЕЕТ УДАЛИТЬ
            \Yii::$app->user->identity->authorization_token->delete();

        }

        return true;
    }

    public function fields()
    {
        return [
            'token'
        ];
    }


    /**
     * Генерируем новый токен для указанного пользователя
     *
     * @param $userId
     * @param int $source
     * @return TokenAuthorization
     */
    public function generateAuthToken($userId, $source = 1)
    {

        $authorization = new AuthCredentials();
        $authorization->credential = uniqid();
        $authorization->validation = uniqid();
        $authorization->type = 'token';
        $authorization->user_id = $userId;

        if (!$authorization->save()) {
            return false;
        }

        return $authorization;
    }
}
