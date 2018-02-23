<?php
namespace api\modules\users\models;

use api\models\TokenAuthorization;
use api\models\User;
use common\components\Model;

class Registration extends Model
{
    public $email = null;
    public $password = null;
    public $guid = null;

    public $token = null;

    public function rules()
    {
        return [
            // Временная регистрация
            [['guid'], 'required', 'on' => ['temporary']],
            [['guid'], 'string', 'min'=>10, 'max'=>50],
            [['guid'], function() {
                $exists = User::find()->andWhere([
                    'guid' => $this->guid
                ])->one();
                if ($exists) {
                    $this->addError('guid', 'GUID_ALREADY_EXISTS');
                    return false;
                }
                return true;
            }, 'on' => ['temporary']],


            // Обычная регистрация с email и паролем
            [['password'], 'string', 'min'=>6, 'max'=>'50', 'on' => ['default']],
            [['email', 'password'], 'required', 'on' => ['default']],
            [['email'], 'email'],
            [['email'], function() {
                $exists = User::find()->andWhere([
                    'email' => $this->email
                ])->one();
                if ($exists) {
                    $this->addError('email', 'EMAIL_ALREADY_EXISTS');
                    return false;
                }
                return true;
            }, 'on' => ['default']]
        ];
    }

    public function fields()
    {
        return [
            'token',
            'email',
            'guid'
        ];
    }


    /**
     * Заполнение модели из запроса
     *
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $this -> email = \Yii::$app->request->getBodyParam('email');
        $this -> password = \Yii::$app->request->getBodyParam('password');
        $this -> guid = \Yii::$app->request->getHeaders()->get('X-GUID');
        return true;
    }


    /**
     * В зависимости от пришедших данных, получаем либо временную регистрацию, либо постоянную :)
     *
     * @return string
     */
    public function getScenario()
    {
        $this -> scenario = $this -> guid ? 'temporary' : 'default';
        return parent::getScenario();
    }


    public function save()
    {
        if ($this->validate())
        {
            $transaction = \Yii::$app->db->beginTransaction();

            $user = new User();
            $user->attributes = $this->attributes;
            $saveRes = $user->save();
            if (!$saveRes) {
                return false;
            }

            // Выдаем авторизационный токен
            $auth = new Auth();
            $authToken = $auth->generateAuthToken($user->id, TokenAuthorization::AUTH_MOBILE);
            if ($authToken === false) {
                $this->addError('email', 'Unable to create access token');
                return false;
            }

            $this->token = $authToken->token;
            $transaction->commit();
            return true;
        }
        return false;
    }
}