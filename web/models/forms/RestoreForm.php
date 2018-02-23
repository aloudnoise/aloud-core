<?php

namespace app\models\forms;


use app\models\RestoreToken;
use app\models\Users;
use app\traits\BackboneRequestTrait;
use common\components\Model;
use common\models\AuthCredentials;
use yii\db\Expression;

class RestoreForm extends Model
{
    use BackboneRequestTrait;

    const SCENARIO_REQUEST = 'request';
    const SCENARIO_RESET = 'reset';

    public $verifyCode;
    public $email = null;
    public $password = null;
    public $password_duplicate = null;
    public $fk = null;

    public function scenarios()
    {
        return [
            self::SCENARIO_REQUEST => ['email', 'verifyCode'],
            self::SCENARIO_RESET => ['password', 'password_duplicate', 'fk'],
        ];
    }


    public function rules()
    {
        return [
            ['verifyCode', 'captcha', 'captchaAction' => 'auth/captcha', 'on' => self::SCENARIO_REQUEST],
            ['verifyCode', 'required', 'on' => self::SCENARIO_REQUEST],
            ['verifyCode', 'trim', 'on' => self::SCENARIO_REQUEST],

            ['email', 'trim', 'on' => self::SCENARIO_REQUEST],
            ['email', 'required', 'on' => self::SCENARIO_REQUEST],
            ['email', 'email', 'on' => self::SCENARIO_REQUEST],

            // Форма смены пароля
            [['password', 'password_duplicate', 'fk'], 'required', 'on' => self::SCENARIO_RESET],
            ['password', 'string', 'max' => 72, 'on' => self::SCENARIO_RESET],
            ['password_duplicate', 'compare', 'compareAttribute' => 'password', 'message' => \Yii::t('main', 'Пароли не совпадают.'), 'on' => self::SCENARIO_RESET],

            ['fk', 'trim', 'on' => self::SCENARIO_RESET],
            ['fk', 'required', 'on' => self::SCENARIO_RESET],
            ['fk', 'string', 'max' => 72, 'min' => 6, 'on' => self::SCENARIO_RESET],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('main', 'Пароль'),
            'password_duplicate' => \Yii::t('main', 'Повтор'),
            'fk' => \Yii::t('main', 'Токен')
        ];
    }


    /**
     * Высылаем письмо с ссылкой
     *
     * @return bool
     */
    public function sendRecoveryMessage()
    {
        if (!$this->validate()) {
            return false;
        }

        // Капча верна, email тоже
        $user = AuthCredentials::findOne([
            'credential' => $this->email,
            'type' => 'email'
        ]);

        if (!$user) {
            $this->addError('email', \Yii::t('main', 'Такой email не найден в базе данных.'));
            return false;
        }

        // Создаем токен и отправялем на почту
        $token = $this->generateToken($user->user_id);
        if (!$token) {
            $this->addError('email', \Yii::t('main', 'Не удалось создать токен, пожалуйста, сообщите в службу поддержки.'));
            return false;
        }

        // Отправялем письмо
        try {
            $isSend = \Yii::$app->mailer->compose('forget', [
                'user' => $user,
                'token' => $token,
            ])
                ->setFrom(\Yii::$app->params['ROBOT_EMAIL'])
                ->setTo($user->credential)
                ->setSubject(\Yii::t('main', 'Восстановление доступа'))
                ->send();
        } catch (\Exception $exception) {
            $this->addError('html', \Yii::t('main', $exception->getMessage()));
            return false;
        }

        if (!$isSend) {
            $this->addError('html', \Yii::t('main', 'Не удалось выслать инструкицю на ящик, пожалуйста, обратитесь в службу поддержки.'));
            return false;
        }

        \Yii::$app->session->setFlash(
            'ok',
            \Yii::t('main', 'Инструкция для смены пароля была выслана на ваш ящик.')
        );
        return true;
    }

    /**
     * Создаем токен
     *
     * @param $userId
     * @return RestoreToken|bool
     */
    protected function generateToken($userId)
    {
        $token = new RestoreToken();
        $token->user_id = (int)$userId;
        $token->token = md5(time() * random_int(1, 99999999));
        $token->ts = new Expression('NOW()');
        $token->expiration_ts = new Expression('NOW() + INTERVAL \'1 DAY\'');
        $token->info = json_encode([
            'ip' => $_SERVER['REMOTE_ADDR'],
            'browser' => $_SERVER['HTTP_USER_AGENT']
        ]);

        if (!$token->save()) {
            return false;
        }

        return $token;
    }


    public function validateToken()
    {
        if (!$this->validate(['fk'])) {
            $this->addError('fk', \Yii::t('main', 'Указан не верный токен.'));
            return false;
        }

        // Ищем в базе
        $token = RestoreToken::find()
            ->where([
                'token' => $this->fk,
                'used_ts' => null
            ])
            ->andWhere('expiration_ts > now()')
            ->orderBy('ts DESC')
            ->limit(1)
            ->one();

        if (!$token) {
            $this->addError('fk', \Yii::t('main', 'Указаный токен не найден, возможно истёк срок действия.'));
            return false;
        }

        // Можно менять, токен найден
        return $token;
    }


    /**
     * Resets user's password.
     *
     * @param RestoreToken $token
     * @return bool
     */
    public function resetPassword(RestoreToken $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        $auths = AuthCredentials::find()->andWhere([
            'user_id' => $token->user_id
        ])->all();

        $transaction = \Yii::$app->db->beginTransaction();

        foreach ($auths as $auth) {
            $auth->scenario = 'change_password';
            $auth->validation = $this->password;
            if (!$auth->save()) {
                \Yii::$app->session->setFlash(
                    'danger',
                    \Yii::t('main', 'Произошла ошибка во время изменения пароля.')
                );
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        \Yii::$app->session->setFlash('success', \Yii::t('main', 'Пароль успешно изменен!'));
        $token->close();
        return true;
    }
}