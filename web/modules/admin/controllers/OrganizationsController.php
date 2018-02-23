<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 01.11.2017
 * Time: 0:16
 */

namespace app\modules\admin\controllers;


use app\components\Controller;
use app\modules\admin\models\OrganizationsForm;
use app\traits\BackboneRequestTrait;
use common\components\ActiveRecord;

class OrganizationsController extends Controller
{

    public function actionAdd()
    {

        $model = new OrganizationsForm();

        if (\Yii::$app->request->post("OrganizationsForm")) {

            $model->attributes = \Yii::$app->request->post("OrganizationsForm");
            if ($model->save()) {
                \Yii::$app->session->addFlash('success', 'OK');
                return $this->renderJSON([]);
            }
            return $this->renderJSON($model->getErrors(), true);

        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['login','password','name','logo'], true);

        return $this->render("form.twig", [
            'model' => $model
        ]);


    }

}