<?php
namespace app\modules\tests\controllers;

use app\helpers\OrganizationUrl;
use app\models\forms\QuestionsFilterForm;
use app\models\Questions;
use app\modules\tests\components\Controller;
use common\models\Themes;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;

class QuestionsController extends Controller
{

    public function actionIndex()
    {

        $filter = new Themes();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $themes = Themes::find()->byOrganization()->with([
            "questions"
        ]);

        $filter->applyFilter($themes);

        $themes->orderBy("ts DESC");

        $provider = new ActiveDataProvider([
            'query' => $themes,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        \Yii::$app->data->filter = QuestionsFilterForm::arrayAttributes($filter, [], ['search'], true);

        return $this->render("index", [
            "provider" => $provider,
            "filter" => $filter,
        ]);

    }

//    public function actionIndex()
//    {
//        $filter = new QuestionsFilterForm();
//        if (\Yii::$app->request->get("filter")) {
//            $filter->attributes = \Yii::$app->request->get("filter");
//        }
//
//        $questions = Questions::find()->byOrganization()->with([
//            "answers",
//            "theme"
//        ]);
//
//        $filter->applyFilter($questions);
//
//        $questions->orderBy("ts DESC");
//
//        $provider = new ActiveDataProvider([
//            'query' => $questions,
//            'pagination' => [
//                'pageSize' => 30
//            ]
//        ]);
//
//        $themes = Themes::find()
//            ->byOrganization()
//            ->all();
//
//        \Yii::$app->data->filter = QuestionsFilterForm::arrayAttributes($filter, [], ['search','pr'], true);
//
//        return $this->render("index", [
//            "provider" => $provider,
//            "themes" => $themes,
//            "filter" => $filter,
//        ]);
//    }

    public function actionDelete()
    {
        $model = Questions::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO QUESTION PROVIDED");

        $model->delete();

        $get = \Yii::$app->request->get();
        unset($get['id']);

        \Yii::$app->response->redirect(OrganizationUrl::to(array_merge(["/tests/questions/index"], $get)), [
            "target" => "normal"
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
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }

}