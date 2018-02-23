<?php
namespace app\modules\admin;

use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['SUPER'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }
}
?>