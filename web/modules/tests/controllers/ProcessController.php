<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 12.12.2017
 * Time: 21:30
 */

namespace app\modules\tests\controllers;


use app\helpers\OrganizationUrl;
use app\models\Questions;
use app\models\relations\TestQuestion;
use app\models\results\TestResults;
use app\models\Tests;
use app\modules\tests\components\Controller;
use app\traits\BackboneRequestTrait;
use common\models\From;
use common\models\Themes;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\NotAcceptableHttpException;

class ProcessController extends Controller
{

    public function actionBegin()
    {

        $model = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TEST PROVIDED");

        $qids = TestQuestion::find()->byOrganization()->andWhere([
            "target_id" => $model->id
        ])->indexBy("related_id")->select("related_id")->asArray()->column();

        $questions = Questions::find()->byOrganization()->andWhere([
            "in", "id", $qids
        ])->all();

        $query = TestResults::findFrom();
        $result = $query
            ->byOrganization()
            ->andWhere([
                "test_id" => \Yii::$app->request->get("id"),
                "user_id" => \Yii::$app->user->id,
            ])->one();

        if ($result AND ($result->finished OR $result->isExpired) AND !$model->is_repeatable) {
            \Yii::$app->response->redirect(OrganizationUrl::to(['/tests/process/run', 'id' => $model->id]));
        }

        $assign = From::instance()->getAssignedRelation($model->id, 'test');
        if ($assign AND $assign->password AND \Yii::$app->request->post("test_password")) {
            if ($assign->password == \Yii::$app->request->post("test_password")) {
                $assign->storedPassword = \Yii::$app->request->post("test_password");
                \Yii::$app->response->redirect(OrganizationUrl::to(['/tests/process/run', 'id' => $model->id]));
            } else {
                $assign->addError("password", \Yii::t("main","Неверный пароль"));
            }
        }

        \Yii::$app->data->test = Tests::arrayAttributes($model, [],  [], true);

        return $this->render("begin", [
            "model" => $model,
            "assign" => $assign,
            "questions" => $questions,
            "result" => $result
        ]);
    }

    public function actionRun()
    {

        $test = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$test) throw new Exception("NO TEST EXISTS");

        /* @var $practice_result_query \common\components\ActiveQuery */
        $query = TestResults::findFrom();
        $model = $query
            ->byOrganization()
            ->andWhere([
                "test_id" => \Yii::$app->request->get("id"),
                "user_id" => \Yii::$app->user->id,
            ]);

        $model->orderBy("id DESC");
        $model = $model->one();

        $assign = From::instance()->getAssignedRelation($test->id, 'test');

        if ($assign AND $assign->password) {
            if (($model AND !$model->finished AND !$model->isExpired) OR !$model) {
                if (!$assign->storedPassword) \Yii::$app->response->redirect(OrganizationUrl::to(["/tests/process/begin", "id" => $test->id]));
            }
        }

        if (!$model OR ($model AND $model->finished AND $test->is_repeatable)) {

            $model = new TestResults();
            $model->user_id = \Yii::$app->user->id;
            $model->test_id = \Yii::$app->request->get("id");
            $model->finished = null;

            if ($assign AND $assign->criteria) {
                $model->criteria = $assign->criteria;
            }

            $userOptions = From::instance()->getAssignedRelation($test->user_id, 'user');
            if ($userOptions AND $userOptions->criteria) {
                $model->criteria = $userOptions->criteria;
            }

            $model->setTestInfo($test);
            $model->save();

        } else if ($model->finished) {
            \Yii::$app->response->redirect(OrganizationUrl::to(["/tests/process/finish", "id"=>$model->id]));
        }

        if ($model->isExpired) {
            \Yii::$app->response->redirect(OrganizationUrl::to(["/tests/process/finish", "id"=>$model->id]));
        }

        $questions = Questions::find()
            ->byOrganization()
            ->with([
                "answers" => function($aq) {
                    return $aq; //->orderBy("random()");
                }
            ])
            ->andWhere([
                "in", "id", $model->infoJson['questions']
            ])->all();

        \Yii::$app->data->questions = Questions::arrayAttributes($questions, [
            "answers" => [
                "fields" => ['id','nameByLang']
            ]
        ], array_merge((new Questions())->attributes(), ['nameByLang','correct_count']), true);
        \Yii::$app->data->model = TestResults::arrayAttributes($model, [], array_merge((new TestResults())->attributes(), ["isExpired", "timeLeft", "infoJson"]), true);
        \Yii::$app->data->test = Tests::arrayAttributes($test, [], array_merge((new Tests())->attributes(), ["infoJson"]), true);

        return $this->render("process", [
            "test" => $test,
            "model" => $model
        ]);

    }

    public function actionFinish()
    {
        $test = TestResults::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$test) \Yii::$app->response->redirect(\Yii::$app->request->get("return") ? : OrganizationUrl::to(["/tests/base/index"]));

        if (!$test->finished) {
            $test->finish();
        }

        if (!empty($test->infoJson['by_themes'])) {

            $themes = Themes::find()->byOrganization()->andWhere([
                'in', 'id', array_keys($test->infoJson['by_themes'])
            ])->orderBy("name")->all();

        }

        \Yii::$app->data->test = TestResults::arrayAttributes($test, [], [], true);

        return $this->render("finished", [
            "test" => $test,
            "themes" => $themes ? : null
        ]);
    }

    public function actionResult()
    {

        $model = TestResults::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TEST EXISTS");

        $test = Tests::find()->byPk($model->test_id)->one();

        $questions = Questions::find()
            ->byOrganization()
            ->with([
                "answers" => function($aq) {
                    return $aq; //->orderBy("random()");
                }
            ])
            ->andWhere([
                "in", "id", $model->infoJson['questions']
            ])->all();

        \Yii::$app->data->questions = Questions::arrayAttributes($questions, [
            "answers" => [
                "fields" => ['id','nameByLang', 'is_correct']
            ]
        ], array_merge((new Questions())->attributes(), ['nameByLang','correct_count']), true);
        \Yii::$app->data->model = TestResults::arrayAttributes($model, [], array_merge((new TestResults())->attributes(), ["isExpired", "timeLeft", "infoJson"]), true);
        \Yii::$app->data->test = Tests::arrayAttributes($test, [], array_merge((new Tests())->attributes(), ["infoJson"]), true);

        return $this->render("result", [
            "test" => $test,
            "model" => $model
        ]);
    }


    public function actionDelete()
    {
        $model = TestResults::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TEST PROVIDED");

        if ($model->user_id == \Yii::$app->user->id) {
            $model->delete();
        }

        \Yii::$app->response->redirect(OrganizationUrl::to(["/tests/base/view", 'id' => $model->test_id]), [
            "target" => "normal"
        ]);
    }

    public function actionDiscard()
    {

        if (\Yii::$app->request->get("id")) {
            $model = TestResults::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        }

        if (!$model) throw new NotAcceptableHttpException("NO RESULT");

        if (\Yii::$app->request->post("TestResults"))
        {
            $model->status_note = \Yii::$app->request->post("TestResults")['status_note'];
            $model->status = TestResults::STATUS_DISCARDED;

            if ($model->save()) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Результат успешно сброшен"));
                return $this->renderJSON([
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['status_note'], true);

        return $this->render("discard", [
            "model" => $model
        ]);


    }

    public function actionRefreshCriteria()
    {

        $test = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$test) throw new NotAcceptableHttpException();
        if (!\Yii::$app->request->get("from")) throw new NotAcceptableHttpException("NO");

        $relationModel = From::instance()->getAssignedRelation($test->id, 'test');
        if (!$relationModel) throw new NotAcceptableHttpException();

        $results = TestResults::findFrom()->andWhere([
            'IS NOT', 'finished', null
        ])->all();

        if ($relationModel->criteria) {
            foreach ($results as $result) {
                $result->criteria = $relationModel->criteria;
                $result->calculateCriteria();
                $result->save();
                \Yii::$app->session->addFlash("success", \Yii::t("main", "Критерии пересчитаны"));
            }
        }

        \Yii::$app->response->redirect(\Yii::$app->request->get('return'));

    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['begin', 'run', 'finish']
                    ],
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