<?php
namespace app\modules\tests\controllers;

use app\helpers\ArrayHelper;
use app\helpers\OrganizationUrl;
use app\models\DicValues;
use app\models\Events;
use app\models\relations\EventCourse;
use app\models\relations\EventTest;
use app\models\results\TestResults;
use app\models\Tests;
use app\modules\tests\components\Controller;
use app\traits\BackboneRequestTrait;
use common\models\forms\FilterForm;
use common\models\From;
use common\models\Tags;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\NotAcceptableHttpException;

class BaseController extends Controller
{

    public function actionIndex()
    {

        $filter = new FilterForm();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $tests = Tests::find()->byOrganization()->with([
            "tags",'questions','themes'
        ]);

        if ($filter->search) {
            $tests->andWhere(["LIKE", "LOWER(name)", mb_strtolower($filter->search, "UTF-8")]);
        }

        $filter->applyFilter($tests);

        $tests->orderBy("ts DESC");

        $provider = new ActiveDataProvider([
            'query' => $tests,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'tests'
        ])->asArray()->column();

        return $this->render("index", [
            "provider" => $provider,
            "filter" => $filter,
            "tags" => $tags
        ]);
    }

    public function actionAssigned()
    {
        $events = Events::getCurrentEvents();

        $ids = ArrayHelper::map($events, 'id', 'id');

        $tests = EventTest::find()
            ->byOrganization()
            ->with([
                'test'
            ])
            ->andWhere([
                'in', 'target_id', $ids
            ])
            ->all();

        $courses = EventCourse::find()
            ->byOrganization()
            ->joinWith([
                'course' => function($q) {
                    return $q->joinWith([
                        'tags',
                        'lessons.tests.test'
                    ]);
                }
            ])
            ->andWhere([
                'IS NOT', 'lesson_test.id', null
            ])
            ->andWhere([
                'in', 'event_course.target_id', $ids
            ])
            ->all();

        return $this->render("assigned", [
            'events' => $events,
            'courses' => $courses,
            'tests' => $tests
        ]);
    }

    public function actionAdd()
    {

        $model = new Tests();
        $model->loadDefaultValues();
        $model->qcount = 30;
        $model->time = 30;
        if (\Yii::$app->request->get("id")) {
            $model = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        }

        if (\Yii::$app->request->post("Tests"))
        {
            $model->attributes = \Yii::$app->request->post("Tests");

            if ($model->save()) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Тест успешно добавлен"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/tests/constructor/compile", "id"=>$model->id]),
                    "target" => "_full"
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'tests'
        ])->asArray()->column();
        \Yii::$app->data->model = Tests::arrayAttributes($model, [],  array_merge((new Tests())->attributes(), ['tagsString']), true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();

        return $this->render("form", [
            "model" => $model
        ]);

    }

    public function actionView()
    {

        $model = Tests::find()
            ->byOrganization()
            ->with([
                "questions.question.answers",
                "themes.theme"
            ])
            ->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TEST PROVIDED");

        $result = TestResults::getMyResult($model->id, new From(['testing', $model->id, 'process']));



        return $this->render("view", [
            "model" => $model,
            "result" => $result
        ]);
    }

    public function actionOptions()
    {

        $test = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$test) throw new NotAcceptableHttpException();
        if (!\Yii::$app->request->get("from")) throw new NotAcceptableHttpException("NO");

        $relationModel = From::instance()->getAssignedRelation($test->id, 'test');
        if (!$relationModel) throw new NotAcceptableHttpException();
        if (\Yii::$app->request->post("TestOptions")) {
            $relationModel->attributes = \Yii::$app->request->post("TestOptions");
            if ($relationModel->save()) {
                return $this->renderJSON([
                    'redirect' => \Yii::$app->request->get('return') ?: null
                ]);
            } else {
                return $this->renderJSON($relationModel->getErrors(), true);
            }
        }

        $criteria_templates = DicValues::findByDic("test_criteria_templates");
        if (\Yii::$app->request->get("template_id")) {
            $relationModel->criteria = $criteria_templates[\Yii::$app->request->get("template_id")]->infoJson['template'];
        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($relationModel, [], ['password', 'criteria'], true);

        return $this->render("options", [
            'test' => $test,
            'model' => $relationModel,
            'criteria_templates' => $criteria_templates
        ]);

    }

    public function actionDelete()
    {
        $model = Tests::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TEST PROVIDED");

        if ($model->canEdit) {
            $model->delete();
        }

        \Yii::$app->response->redirect(OrganizationUrl::to(["/tests/base/index"]), [
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
                        'actions' => ['assigned'],
                        'roles' => ['@']
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