<?php
namespace api\modules\users\controllers;


use api\components\ActiveController;

class PushTokensController extends ActiveController
{

    public $modelClass = 'api\models\PushTokens';

    public function actionDelete()
    {

        $token = \Yii::$app->request->get("id");

        $model = $this->modelClass;
        $models = $model::find()->andWhere([
            "token" => $token
        ])->all();
        if ($models) {
            foreach ($models as $m) {
                if ($m->user_id == \Yii::$app->user->id) {
                    $m->delete();
                }
            }
        }
        return [];
    }

}