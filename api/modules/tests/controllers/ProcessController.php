<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 05.12.2017
 * Time: 16:39
 */

namespace api\modules\tests\controllers;


use api\components\ActiveController;
use api\models\results\TestResults;
use yii\web\MethodNotAllowedHttpException;

class ProcessController extends ActiveController
{

    public $modelClass = 'api\models\results\TestResults';

    public function checkAccess($action, $model = null, $params = [])
    {

        if ($action == 'update') {

            /* @var $model TestResults */
            if ($model->isExpired OR $model->finished OR $model->user_id != \Yii::$app->user->id) {
                throw new MethodNotAllowedHttpException();
            }

        }

        parent::checkAccess($action, $model, $params);
    }

}