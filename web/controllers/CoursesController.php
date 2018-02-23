<?php

namespace app\controllers;
use app\components\Controller;
use app\helpers\ArrayHelper;
use app\helpers\OrganizationUrl;
use app\models\CourseLessons;
use app\models\Courses;
use app\models\Events;
use app\models\Materials;
use app\models\relations\EventCourse;
use app\models\Tasks;
use common\components\ActiveRecord;
use common\models\forms\FilterForm;
use common\models\Organizations;
use common\models\Relations;
use common\models\relations\LessonTask;
use common\models\results\TestResults;
use common\models\Tags;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\helpers\Common;
use yii\filters\AccessControl;

/**
 * Class MainController
 * @package app\controllers
 */
class CoursesController extends Controller {

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \Yii::$app->urlManager->createUrl("/main/index"));
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Курсы обучения"), \Yii::$app->urlManager->createUrl("/courses/index"));
        return $p;
    }

    public function actionIndex()
    {

        $filter = new FilterForm();
        if (!\Yii::$app->user->can("base_teacher")) {
            $filter->pr = 2;
        }

        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $courses = Courses::find()->byOrganization()->with("lessons", "tags", "views");
        $courses->notDeleted();

        if ($filter->search) {
            $courses->andWhere(["LIKE", "LOWER(name)", mb_strtolower($filter->search, "UTF-8")]);
        }

        if ($filter->pr == 2) {
            $courses->andWhere(['state' => Courses::PUBLISHED]);
        } else {
            $courses->byUser();
        }

        $filter->applyFilter($courses);

        $courses->orderBy("ts DESC");

        $provider = new ActiveDataProvider([
            'query' => $courses,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        \Yii::$app->data->filter = Courses::arrayAttributes($filter, [], ['search','pr'], true);

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'courses'
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

        $courses = EventCourse::find()
            ->byOrganization()
            ->with([
                'course.tags'
            ])
            ->andWhere([
                'in', 'target_id', ArrayHelper::map($events, 'id', 'id')
            ])
        ->all();

        return $this->render("assigned", [
            'events' => $events,
            'courses' => $courses
        ]);
    }

    public function actionAdd()
    {

        $model = new Courses();
        if (\Yii::$app->request->get('id')) {
            $model = Courses::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if (\Yii::$app->request->post("Courses"))
        {
            $model->attributes = \Yii::$app->request->post("Courses");

            if ($model->save()) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Курс успешно добавлен"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/courses/view", "id"=>$model->id]),
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'courses'
        ])->asArray()->column();
        \Yii::$app->data->model = Courses::arrayAttributes($model, [],  array_merge((new Courses())->attributes(), ['tagsString']), true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();

        return $this->render("form", [
            "model" => $model
        ]);
    }

    public function actionView()
    {

        $model = Courses::find()
            ->byOrganization()
            ->with([
                "lessons" => function($query) {
                    return $query->notDeleted()->with([
                        "materials" => function($q) {
                            return $q->with([
                                "material" => function($mq) {
                                    return $mq->notDeleted();
                                }
                            ]);
                        },
                        "tests" => function($tq) {
                            return $tq->with([
                                "test"
                            ]);
                        },
                        "tasks" => function($tsq) {
                            $tsq->notDeleted();
                            return $tsq->with([
                                "task" => function($tsq) {
                                    return $tsq->notDeleted();
                                }
                            ]);
                        },
                    ])->orderBy("ts");
                }
            ])
            ->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO COURSE PROVIDED");

        $model->addView();

        if (\Yii::$app->request->get("a") == "add_lesson") {

            $course_lesson = new CourseLessons();
            $course_lesson->course_id = \Yii::$app->request->get("id");
            if (\Yii::$app->request->get('l_id')) {
                $course_lesson = CourseLessons::find()->byOrganization()->byPk(\Yii::$app->request->get('l_id'))->one();
            }

            if (\Yii::$app->request->post("CourseLessons"))
            {
                $course_lesson->attributes = \Yii::$app->request->post("CourseLessons");
                $course_lesson->type = 1;

                if ($course_lesson->save()) {
                    \Yii::$app->session->setFlash("ok",\Yii::t("main","Урок успешно добавлен"));
                    return $this->renderJSON([
                        "redirect"=>\app\helpers\OrganizationUrl::to(["/courses/view", "id"=>$course_lesson->course_id]),
                        "scroll" => false
                    ]);
                } else {
                    return $this->renderJSON($course_lesson->getErrors(), true);
                }

            }

            \Yii::$app->data->course_lesson = CourseLessons::arrayAttributes($course_lesson, [],  (new CourseLessons())->attributes(), true);

            $form = $this->renderPartial("lesson_form", [
                "model" => $course_lesson
            ]);

        }

        return $this->render("view", [
            "model" => $model,
            "form" => $form ?: false
        ]);
    }

    public function actionViewder()
    {

        $lesson = CourseLessons::find()->byOrganization()->byPk(\Yii::$app->request->get("lid"))->one();
        if (!$lesson) throw new Exception("NO LESSON PROVIDED");

        $der = \common\models\Materials::find()->byPk($lesson->content)->one();

        if (!$der) throw new Exception("NO DER IN LESSON");

        \Yii::$app->data->size = "fullscreen";
        \Yii::$app->data->noHeader = true;

        return $this->render("der", [
            "lesson" => $lesson,
            "der" => $der
        ]);

    }

    public function actionPublish()
    {
        $model = new Courses();
        if (\Yii::$app->request->get('id')) {
            $model = Courses::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($model->canEdit) {
            $model->state = Courses::PUBLISHED;
            $model->save();
        }

        \Yii::$app->session->setFlash("ok",\Yii::t("main","Курс успешно опубликован"));
        \Yii::$app->response->redirect(OrganizationUrl::to(["/courses/view", "id"=>$model->id]));

    }

    public function actionUnpublish()
    {
        $model = new Courses();
        if (\Yii::$app->request->get('id')) {
            $model = Courses::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($model->canEdit) {
            $model->state = Courses::CREATED;
            $model->save();
        }

        \Yii::$app->session->setFlash("ok",\Yii::t("main","Курс успешно опубликован"));
        \Yii::$app->response->redirect(OrganizationUrl::to(["/courses/view", "id"=>$model->id]));

    }

    public function actionDelete()
    {

        $course = Courses::find()->byOrganization()->byPk(\Yii::$app->request->get('cid'))->one();
        if (!$course) {
            throw new Exception("No such course");
        }

        if (!$course->canEdit) {
            throw new Exception("You dont have rights to do this");
        }

        $acts = array(
            1 => array(
                "url"=>OrganizationUrl::to(["/courses/index"]),
                "model"=>'\app\models\Courses'
            ),
            2 => array(
                "url"=>OrganizationUrl::to(["/courses/view", "id"=>$course->id]),
                "model"=>'\app\models\CourseLessons'
            ),
            3 => array(
                "url"=>OrganizationUrl::to(["/courses/view", "id"=>$course->id]),
                "model"=>'\app\models\relations\LessonMaterial'
            ),
            4 => array(
                "url"=>OrganizationUrl::to(["/courses/view", "id"=>$course->id]),
                "model"=>'\app\models\relations\LessonTest'
            ),
            5 => array(
                "url"=>OrganizationUrl::to(["/courses/view", "id"=>$course->id]),
                "model"=>'\app\models\relations\LessonTask'
            ),
        );

        $model = $acts[\Yii::$app->request->get("type")]['model'];
        if ((new $model())->deleteAccess(["id"=>\Yii::$app->request->get("tid")])) {
            if ((new $model())->deleteRequest(["id"=>\Yii::$app->request->get("tid")])) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Удаление прошло успешно"));
            }
        }

        \Yii::$app->response->redirect($acts[\Yii::$app->request->get("type")]['url']);
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
                        'actions' => ['assigned','view', 'viewder']
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
?>