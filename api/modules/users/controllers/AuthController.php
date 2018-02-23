<?php

namespace api\modules\users\controllers;

use api\components\ActiveController;
use yii\web\ServerErrorHttpException;

/**
 * Контроллер авторизации пользователей
 * Class AuthController
 * @package api\modules\users\controllers
 */
class AuthController extends ActiveController
{

    public $modelClass = 'api\modules\users\models\Auth';

    public function actionDelete()
    {
        $model = new $this->modelClass();
        if (!$model->delete()) {
            throw new ServerErrorHttpException('Failed to delete object for unknown reason.');
        }
    }

    public function behaviors()
    {
        $result = parent::behaviors();
        unset($result['authenticator']);
        return $result;
    }
}