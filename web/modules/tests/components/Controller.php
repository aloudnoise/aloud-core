<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 12.12.2017
 * Time: 2:01
 */

namespace app\modules\tests\components;


use app\helpers\OrganizationUrl;

class Controller extends \app\components\Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), OrganizationUrl::to(["/main/index"]));
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Тесты"), OrganizationUrl::to(["/tests/base/index"]));
        return $p;
    }

}