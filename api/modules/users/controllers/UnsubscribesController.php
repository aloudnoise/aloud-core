<?php
namespace api\modules\users\controllers;

use api\components\ActiveController;
use yii\web\MethodNotAllowedHttpException;

class UnsubscribesController extends ActiveController
{
    public $modelClass = 'api\models\Unsubscribes';
}