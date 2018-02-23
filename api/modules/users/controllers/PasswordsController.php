<?php

namespace api\modules\users\controllers;

use api\components\ActiveController;

/**
 * Контроллер смены пароля
 * Class PasswordsController
 * @package api\modules\users\controllers
 */
class PasswordsController extends ActiveController
{
    public $modelClass = 'api\modules\users\models\Passwords';

    public function actionCreate()
    {
        $model = parent::actionCreate();
        if (!$model->hasErrors()) {
            return true;
        }
        return $model;
    }

    public function behaviors()
    {
        $result = parent::behaviors();
        if (\Yii::$app->request->post('hash')) {
            unset($result['authenticator']);
        }
        return $result;
    }
}