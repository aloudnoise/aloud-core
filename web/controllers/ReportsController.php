<?php

namespace app\controllers;
use app\components\Controller;
use app\components\VarDumper;
use app\models\Events;
use app\models\forms\ReportsFilterForm;
use app\models\forms\ReportsForm;
use app\models\results\TestResults;
use common\models\From;
use TinCan\Activity;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * Class MainController
 * @package app\controllers
 */
class ReportsController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \Yii::$app->urlManager->createUrl("/main/index"));
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Отчеты"), \Yii::$app->urlManager->createUrl("/reports/index"));
        return $p;
    }


    public function actionIndex()
    {

        /** @var $filter \app\models\reports\ReportsFilter */
        $filter = \Yii::createObject('app\models\reports\ReportsFilter');
        $filter->attributes = \Yii::$app->request->get('filter');

        $from = new From();
        if ($from->name) {
            $filter->from = $from;
            $filter->setFromAttributes();
        }

        $filter->validate();

        \Yii::$app->data->filter = $filter->attributes;

        return $this->render('index', [
            'filter' => $filter,
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['base_teacher'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?','@']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }


}
?>