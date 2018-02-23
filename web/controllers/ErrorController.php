<?php

namespace app\controllers;

use app;
use yii\filters\AccessControl;

class ErrorController extends app\components\Controller {
    public $layout = "main";
    public function actionIndex()
    {
        return $this->render("index");
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }

}
?>