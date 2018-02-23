<?php
namespace api\controllers;

use api\components\ActiveController;
use api\models\Dic;
use api\models\Dics;
use yii\web\HttpException;

class DicController extends ActiveController
{
    public $modelClass = 'api\models\Dic';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        return $actions;
    }

    public function actionView($id) {
        $dic = Dics::findOne([
            'name' => $id,
            'is_deleted' => 0,
        ]);

        if (!$dic) {
            throw new HttpException(404,'DIC_NOT_FOUND');
        }
        return $dic->values;
    }
}