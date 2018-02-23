<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 23.01.2018
 * Time: 18:42
 */

namespace app\controllers;


use app\components\Controller;
use common\models\forms\HtmlRender;
use common\models\forms\PhpWordHtmlRender;

class ExportController extends Controller
{

    public function actionIndex()
    {

        $model = new HtmlRender();
        if (\Yii::$app->request->post()) {

            $model->attributes = \Yii::$app->request->post();
            return $model->render();

        }
        return false;
    }

}