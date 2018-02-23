<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.02.2018
 * Time: 14:47
 */

namespace app\modules\admin\controllers;


use app\components\Controller;
use app\models\Users;

class UsersController extends Controller
{

    public function actionDelete()
    {
        $id = \Yii::$app->request->get("id");
        $user = Users::find()->byPk($id)->one();
        if ($user->system_role != 'SUPER') {

            \Yii::$app->db->createCommand()
                ->delete("users", [
                    'id' => $id
                ])->execute();

            \Yii::$app->db->createCommand()
                ->delete("relations.event_user", [
                    'related_id' => $id
                ])->execute();

            \Yii::$app->db->createCommand()
                ->delete("results.event_results", [
                    'user_id' => $id
                ])->execute();

            \Yii::$app->db->createCommand()
                ->delete("results.material_results", [
                    'user_id' => $id
                ])->execute();

            \Yii::$app->db->createCommand()
                ->delete("results.task_results", [
                    'user_id' => $id
                ])->execute();

            \Yii::$app->db->createCommand()
                ->delete("results.test_results", [
                    'user_id' => $id
                ])->execute();
        }

    }

}