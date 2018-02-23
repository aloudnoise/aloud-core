<?php

namespace app\controllers;

use app\components\Controller;
use yii\filters\AccessControl;

class BackboneController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['request'],
                    ],
                ],
            ],
        ]);
    }

    public function actionRequest()
    {

        $method = $_SERVER['REQUEST_METHOD'];
        if (\Yii::$app->request->post('method')) {
            $method = \Yii::$app->request->post('method');
        }
        $v = !in_array($method, ["DELETE","PUT","POST"]) ? \Yii::$app->request->get() : \Yii::$app->request->post();
        if (isset($v))        {
            $m = ($v['schema'] ? $v['schema'].'\\' : 'common\models\\').$v['yModel'];
            $m = str_replace("/", '\\', $m);
            $model = new $m();
            return $this->$method($model, $v[(new \ReflectionClass($model))->getShortName()]);
        } else {
            return $this->renderJSON(["noModel" => [\Yii::t("main","Не указана модель")]], true);
        }
    }

    public function GET($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("get",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

    public function POST($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("insert",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

    public function PUT($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("update",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

    public function DELETE($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("delete",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

}
?>