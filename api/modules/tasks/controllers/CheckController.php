<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 16.01.2018
 * Time: 0:51
 */

namespace api\modules\tasks\controllers;


use api\components\ActiveController;
use yii\filters\AccessControl;

class CheckController extends ActiveController
{

    public $modelClass = 'api\models\results\TaskResults';

    public function actions()
    {
        $actions = parent::actions();
        $actions[] = 'update';
        return $actions;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['options'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['teacher']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ]);
    }

}