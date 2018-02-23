<?php

namespace bilimal\web\controllers;

use app\helpers\Url;
use bilimal\web\models\forms\LoginForm;

class AuthController extends \app\controllers\AuthController
{

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

        return $this->render('@app/views/auth/login.twig', [
            'user' => $user
        ]);

    }

}