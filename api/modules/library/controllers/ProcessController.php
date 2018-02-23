<?php
namespace api\modules\library\controllers;

use api\components\ActiveController;
use api\models\results\MaterialResults;
use yii\web\MethodNotAllowedHttpException;

class ProcessController extends ActiveController
{

    public $modelClass = 'api\models\results\MaterialResults';

    public function checkAccess($action, $model = null, $params = [])
    {

        if ($action == 'update') {
            /* @var $model MaterialResults */
            if ($model->user_id != \Yii::$app->user->id) {
                throw new MethodNotAllowedHttpException();
            }

        }

        parent::checkAccess($action, $model, $params);
    }

}