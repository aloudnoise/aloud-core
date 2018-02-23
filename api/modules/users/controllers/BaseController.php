<?php

namespace api\modules\users\controllers;

use api\components\ActiveController;
use yii\web\MethodNotAllowedHttpException;

/**
 * Class PasswordsController
 *
 * @package api\modules\users\controllers
 */
class BaseController extends ActiveController
{
    public $modelClass = 'api\models\User';

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action == 'update' && $model->id != \Yii::$app->user->id)
        {
            throw new MethodNotAllowedHttpException('ACCESS_DENIED');
        }
    }
}