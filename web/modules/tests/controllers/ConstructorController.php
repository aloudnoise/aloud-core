<?php
namespace app\modules\tests\controllers;

use app\helpers\OrganizationUrl;
use app\models\Questions;
use app\models\relations\TestQuestion;
use app\models\relations\TestTheme;
use app\models\Tests;
use app\modules\tests\components\Controller;
use app\modules\tests\models\constructor\FullTheme;
use app\modules\tests\models\constructor\SimpleQuestion;
use app\modules\tests\models\Panel;
use app\traits\BackboneRequestTrait;
use common\models\Themes;
use yii\db\Exception;
use yii\filters\AccessControl;

class ConstructorController extends Controller
{

    public function actionCompile()
    {

        $panel = new Panel();
        $panel->attributes = \Yii::$app->request->get("panel", []);

        $model = $panel->instrumentModel ? new $panel->instrumentModel() : null;
        $questions = [];
        if (\Yii::$app->request->get("id")) {
            $test = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();

            $q_ids = TestQuestion::find()->select(["related_id"])->byOrganization()->andWhere([
                'target_id' => $test->id
            ])->orderBy('ts')->asArray()->column();
            $questions = Questions::find()->andWhere(['in', 'id', $q_ids])->byOrganization()->orderBy("id")->all();

            $themes = TestTheme::find()->byOrganization()->andWhere([
                'target_id' => $test->id
            ])->with([
                'theme'
            ])->all();

            if ($model) {
                $model->test = $test;
            }

        }

        if (\Yii::$app->request->get("theme_id")) {
            $theme = Themes::find()->byOrganization()->byPk(\Yii::$app->request->get("theme_id"))->one();
            $questions = $theme->questions;

            if ($model) {
                $model->theme_id = $theme->id;
            }
        }

        if (\Yii::$app->request->get("q_id")) {
            $model = new SimpleQuestion();
            $model->test = $test;
            $q = Questions::find()->byPk(\Yii::$app->request->get("q_id"))->one();
            if ($q->user_id != \Yii::$app->user->id) {
                throw new Exception("NO");
            }
            $model->loadAttributes($q);
            \Yii::$app->data->editing_question = true;
        }

        if (\Yii::$app->request->get("t_id")) {
            $model = new FullTheme();
            $model->test = $test;
            $t = TestTheme::find()->byPk(\Yii::$app->request->get("t_id"))->one();
            $model->loadAttributes($t);
            \Yii::$app->data->editing_theme = true;
        }

        if (\Yii::$app->request->post("InstrumentForm")) {
            $model->attributes = \Yii::$app->request->post("InstrumentForm");
            $action = \Yii::$app->request->post("InstrumentForm")['submitter'];
            if ($model->save()) {
                return $this->renderJSON($model->getReturn($action) ?: [
                    'redirect' => $action == "continue" ? \Yii::$app->request->url : OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id])
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        if ($model) {
            \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], $model->fields(), true);
        }

        \Yii::$app->data->panel = BackboneRequestTrait::arrayAttributes($panel, [], ['instrument'], true);

        return $this->render("compile", [
            'theme' => $theme,
            'test' => $test,
            'panel' => $panel,
            'questions' => $questions,
            'themes' => $themes,
            'model' => $model
        ]);

    }

    public function actionAssign()
    {

        if (\Yii::$app->request->get("q_id")) {
            $question = Questions::find()->byPk(\Yii::$app->request->get("q_id"))->byOrganization()->one();
            if (!$question) throw new Exception("NO");
        }

        if (\Yii::$app->request->get("id")) {
            $test = Tests::find()->byPk(\Yii::$app->request->get("id"))->byOrganization()->one();
            if (!$test) throw new Exception("NO");
            if ($test->user_id != \Yii::$app->user->id) {
                throw new Exception("NO");
            }

            if ($question) {
                $testQuestion = TestQuestion::find()->andWhere([
                    'target_id' => $test->id,
                    'related_id' => $question->id
                ])->byOrganization()->one();
                if (!$testQuestion) {
                    $testQuestion = new TestQuestion();
                    $testQuestion->target_id = $test->id;
                    $testQuestion->related_id = $question->id;
                    $testQuestion->save();
                }
            }

        }

        \Yii::$app->response->redirect(\Yii::$app->request->get("return"), [
            'scroll' => false
        ]);

    }

    public function actionUnset()
    {
        if (\Yii::$app->request->get("q_id")) {
            $question = Questions::find()->byPk(\Yii::$app->request->get("q_id"))->byOrganization()->one();
            if (!$question) throw new Exception("NO");
        }

        if (\Yii::$app->request->get("t_id")) {
            $theme = TestTheme::find()->byPk(\Yii::$app->request->get("t_id"))->byOrganization()->one();
            if (!$theme) throw new Exception("NO");
        }

        if (\Yii::$app->request->get("id")) {
            $test = Tests::find()->byPk(\Yii::$app->request->get("id"))->byOrganization()->one();
            if (!$test) throw new Exception("NO");
            if ($test->user_id != \Yii::$app->user->id) {
                throw new Exception("NO");
            }

            if ($question) {
                $testQuestion = TestQuestion::find()->andWhere([
                    'target_id' => $test->id,
                    'related_id' => $question->id
                ])->byOrganization()->one();
                if ($testQuestion) {
                    $testQuestion->delete();
                }
            }

            if ($theme) {
                $theme->delete();
            }

        }

        \Yii::$app->response->redirect(OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id]), [
            'scroll' => false
        ]);

    }

    public function actionDelete()
    {
        $question = Questions::find()->byPk(\Yii::$app->request->get("q_id"))->byOrganization()->one();
        if (!$question) throw new Exception("NO");

        if ($question->user_id != \Yii::$app->user->id) throw new Exception("NO");

        $question->delete();

        \Yii::$app->response->redirect(OrganizationUrl::to(['/tests/constructor/compile', 'id' => \Yii::$app->request->get("id")]), [
            'scroll' => false
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