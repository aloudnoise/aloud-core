<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\RegistrationForm;
use app\models\forms\RestoreForm;
use yii\captcha\Captcha;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\rest\Serializer;
use yii\web\HttpException;

class AuthController extends \app\components\Controller
{

    public $layout = 'no_auth';

    public function behaviors()
    {
        $behaviors = array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['language', 'login', 'registration', 'restore', 'recovery', 'captcha'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['logout'],
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
        unset($behaviors['organization']);
        return $behaviors;
    }

    public function actionLanguage()
    {
        return '';
    }

    public function actionLogin()
    {
        $user = new LoginForm();
        if (\Yii::$app->request->post('LoginForm')) {
            $user->load(\Yii::$app->request->post());
            if ($user->validateAndLogin()) {
                return $this->renderJSON([
                    'redirect' => Url::to(['/cabinet/base/index']),
                    'full' => true
                ]);
            } else {
                return $this->renderJSON($user->getErrors(), true);
            }
        }

        \Yii::$app->data->size = 'sm';
        \Yii::$app->data->user = LoginForm::arrayAttributes($user, [], ['login', 'password', 'remember_me'], true);
        \Yii::$app->data->rules = $user->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $user->attributeLabels();

        return $this->render('login.twig', [
            'user' => $user
        ]);

    }

    public function actionRegistration()
    {
        $model = new RegistrationForm();
        if (\Yii::$app->request->post('RegistrationForm')) {
            $model->attributes = \Yii::$app->request->post('RegistrationForm');
            if ($model->save()) {

                \Yii::$app->session->setFlash('ok', \Yii::t('main', 'Вы успешно зарегистрировались'));
                $user = new LoginForm();
                $user->login = $model->login;
                $user->password = \Yii::$app->request->post('RegistrationForm')['password'];
                $user->remember_me = 1;
                if (!$user->validateAndLogin()) {
                    return $this->renderJSON($user->getErrors(), true);
                }
                return $this->renderJSON([
                    'redirect' => Url::to(['/cabinet/base/index'])
                ]);

            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        \Yii::$app->data->model = RegistrationForm::arrayAttributes($model, [], ['login','email','phone','password','fio'], true);

        return $this->render('registration.twig', [
            'model' => $model
        ]);

    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->response->redirect('/main/index', [
            'full' => true,
        ]);
    }

    /**
     * Форма на восстанавление пароля
     *
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionRestore()
    {
        $model = new RestoreForm();
        $model->scenario = RestoreForm::SCENARIO_REQUEST;

        if (\Yii::$app->request->post('RestoreForm')) {
            $model->attributes = \Yii::$app->request->post('RestoreForm');
            if (!$model->sendRecoveryMessage()) {
                return $this->renderJSON($model->getErrors(), true);
            }

            return $this->renderJSON([
                'redirect' => Url::to(['/'])
            ]);
        }

        \Yii::$app->data->model = RestoreForm::arrayAttributes($model, [], ['email', 'verifyCode'], true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();

        return $this->render('restore.twig', [
            'model' => $model,
        ]);
    }

    /**
     * Форма смены пароля с токеном
     *
     * @return string
     * @throws \yii\base\InvalidParamException
     * @throws HttpException
     */
    public function actionRecovery()
    {
        $model = new RestoreForm();
        $model->scenario = RestoreForm::SCENARIO_RESET;

        if (\Yii::$app->request->isPost) {
            // Пришел пост
            if (\Yii::$app->request->post('RestoreForm')) {
                $model->attributes = \Yii::$app->request->post('RestoreForm');
                if (!$token = $model->validateToken()) {
                    return $this->renderJSON($model->getErrors(), true);
                }

                if (!$model->validate()) {
                    return $this->renderJSON($model->getErrors(), true);
                }

                // Меняем пароль
                if (!$model->resetPassword($token)) {
                    $this->addError('fk', \Yii::t('main', 'Не удалось установить пароль.'));
                    return false;
                }

                // Сразу авторизуем пользоватлея
                $userLogin = $token->user->email;
                $userPass = $model->password;
                $auth = new LoginForm();
                $auth->login = $userLogin;
                $auth->password = $userPass;
                if ($auth->validateAndLogin()) {
                    return $this->renderJSON([
                        'redirect' => Url::to(['/main/index']),
                        'full' => true
                    ]);
                }

                // Не удалось авторизовать
                return $this->renderJSON([
                    'redirect' => Url::to(['/auth/login'])
                ]);
            }
        } else {
            // Открытие страницы
            $model->fk = \Yii::$app->request->get('fk');
            if (!$model->validateToken()) {
                throw new HttpException(400, \Yii::t('main', 'Указаный токен не найден, возможно истёк срок действия.'));
            }
        }

        \Yii::$app->data->model = RestoreForm::arrayAttributes($model, [], ['password', 'password_duplicate', 'fk'], true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();

        return $this->render('recovery.twig', [
            'model' => $model,
        ]);
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}