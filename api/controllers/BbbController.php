<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 26.01.2018
 * Time: 2:12
 */

namespace api\controllers;


use api\components\ActiveController;

class BbbController extends ActiveController
{

    public $modelClass = 'api\models\Bbb';
    public function actionCreate()
    {
        \Yii::trace(\Yii::$app->request->post(), 'bbb');
        var_dump('asd');
        die();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        unset($behaviors['access']);
        return $behaviors;
    }

}