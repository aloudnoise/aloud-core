<?php

namespace app\controllers;

use app\components\Controller;
use app\helpers\ArrayHelper;
use app\helpers\OrganizationUrl;
use app\models\DicValues;
use app\models\Events;
use app\models\relations\EventCourse;
use app\models\relations\EventTask;
use app\models\results\TaskResults;
use app\models\Tasks;
use app\traits\BackboneRequestTrait;
use common\models\forms\FilterForm;
use common\models\From;
use common\models\Tags;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotAcceptableHttpException;

class TasksController extends Controller
{
    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \Yii::$app->urlManager->createUrl("/main/index"));
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Задания"), \Yii::$app->urlManager->createUrl("/tasks/index"));
        return $p;
    }

    public function actionIndex()
    {
        $filter = new FilterForm();

        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $tasks = Tasks::find()->byOrganization()->notDeleted();

        if ($filter->pr == 2) {
            $tasks->andWhere([
                'is_shared' => 1
            ]);
        }

        if ($filter->search) {
            $tasks->andWhere(["LIKE", "LOWER(name)", mb_strtolower($filter->search, "UTF-8")]);
        }

        $filter->applyFilter($tasks);

        $tasks->orderBy("ts DESC");

        $provider = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        \Yii::$app->data->filter = Tasks::arrayAttributes($filter, [], ['search'], true);

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'tasks'
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

        $tasks = EventTask::find()
            ->byOrganization()
            ->with([
                'task'
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
                        'lessons.tasks.task'
                    ]);
                }
            ])
            ->andWhere([
                'IS NOT', 'lesson_task.id', null
            ])
            ->andWhere([
                'in', 'event_course.target_id', $ids
            ])
            ->all();

        return $this->render("assigned", [
            'events' => $events,
            'courses' => $courses,
            'tasks' => $tasks
        ]);
    }


    public function actionAdd()
    {
        if (\Yii::$app->request->get('id')) {
            $model = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        } else {
            $model = new Tasks();
            $model->time = 30;
        }



        if (\Yii::$app->request->post('Tasks')) {

            $model->attributes = \Yii::$app->request->post('Tasks');

            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Задание успешно добавлено/обновлено"));
                return $this->renderJSON([
                    "redirect"=> OrganizationUrl::to(["/tasks/index"])
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'tasks'
        ])->asArray()->column();
        \Yii::$app->data->model = Tasks::arrayAttributes($model, [], array_merge((new Tasks())->attributes(), ['tagsString', 'files']), true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();

        return $this->render("form", [
            "model" => $model
        ]);

    }

    public function actionView()
    {
        $model = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("Task not found");

        return $this->render("view", [
            "model" => $model
        ]);
    }

    public function actionDelete()
    {
        $model = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TASK PROVIDED");

        if ($model->canEdit) {
            $model->delete();
        }

        \Yii::$app->response->redirect(OrganizationUrl::to(["/tasks/index"]), [
            "target" => "normal"
        ]);

    }

    public function actionBegin()
    {
        $model = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TASK PROVIDED");

        $query = TaskResults::findFrom();
        $result = $query
            ->byOrganization()
            ->andWhere([
                "task_id" => \Yii::$app->request->get("id"),
                "user_id" => \Yii::$app->user->id,
            ])->one();

        if ($result AND ($result->finished OR $result->isExpired)) {
            \Yii::$app->response->redirect(OrganizationUrl::to(['/tasks/process', 'id' => $model->id]));
        }


        $assign = From::instance()->getAssignedRelation($model->id, 'task');
        if ($assign AND $assign->password AND \Yii::$app->request->post("task_password")) {
            if ($assign->password == \Yii::$app->request->post("task_password")) {
                $assign->storedPassword = \Yii::$app->request->post("task_password");
                \Yii::$app->response->redirect(OrganizationUrl::to(['/tasks/process', 'id' => $model->id]));
            } else {
                $assign->addError("password", \Yii::t("main","Неверный пароль"));
            }
        }

        \Yii::$app->data->test = Tasks::arrayAttributes($model, [],  [], true);

        return $this->render("begin", [
            "model" => $model,
            "assign" => $assign,
            "result" => $result
        ]);
    }

    /**
     * Выполнение задания
     *
     * @return string
     * @throws Exception
     */
    public function actionProcess()
    {
        $task = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$task) throw new Exception("NO TASK EXISTS");

        /* @var $practice_result_query \common\components\ActiveQuery */
        $query = TaskResults::findFrom();
        $model = $query
            ->byOrganization()
            ->andWhere([
                "task_id" => \Yii::$app->request->get("id"),
                "user_id" => \Yii::$app->user->id,
            ]);

        $model->orderBy("id DESC");
        $model = $model->one();

        $assign = From::instance()->getAssignedRelation($task->id, 'task');

        if ($assign AND $assign->password) {
            if (($model AND !$model->finished AND !$model->isExpired) OR !$model) {
                if (!$assign->storedPassword) \Yii::$app->response->redirect(OrganizationUrl::to(["/tasks/begin", "id" => $task->id]));
            }
        }

        if (!$model) {

            $model = new TaskResults();
            $model->user_id = \Yii::$app->user->id;
            $model->task_id = \Yii::$app->request->get("id");
            $model->finished = null;

            if ($assign AND $assign->criteria) {
                $model->criteria = $assign->criteria;
            }

            $userOptions = From::instance()->getAssignedRelation($task->user_id, 'user');
            if ($userOptions AND $userOptions->criteria) {
                $model->criteria = $userOptions->criteria;
            }

            $model->save();

        } else if ($model->finished) {
            \Yii::$app->response->redirect(OrganizationUrl::to(["/tasks/finish", "id"=>$model->id]));
        }

        if ($model->isExpired) {
            \Yii::$app->response->redirect(OrganizationUrl::to(["/tasks/finish", "id"=>$model->id]));
        }

        if (\Yii::$app->request->post("TaskResults")) {

            $model->answer = \Yii::$app->request->post("TaskResults")['answer'];
            if ($model->finish()) {
                return $this->renderJSON([
                    'redirect' => OrganizationUrl::to(["/tasks/finish", "id"=>$model->id])
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->model = TaskResults::arrayAttributes($model, [], array_merge((new TaskResults())->attributes(), ["isExpired", "timeLeft"]), true);
        \Yii::$app->data->task = Tasks::arrayAttributes($task, [], array_merge((new Tasks())->attributes()), true);

        return $this->render("process", [
            "task" => $task,
            "model" => $model
        ]);
    }

    public function actionFinish()
    {
        $model = TaskResults::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) \Yii::$app->response->redirect(\Yii::$app->request->get("return") ? : OrganizationUrl::to(["/tasks/index"]));

        if (!$model->finished) {
            $model->finish();
        }

        return $this->render("finished", [
            "model" => $model,
        ]);
    }

    /**
     * Проверка задания
     *
     * @return string
     * @throws Exception
     */
    public function actionCheck()
    {
        $task = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$task) throw new Exception("NO TASK EXISTS");

        $models = TaskResults::findFrom()
            ->byOrganization()
            ->andWhere([
                "task_id" => \Yii::$app->request->get("id"),
            ])
            ->andWhere([
                'IS NOT', 'finished', null
            ])
            ->orderBy("ts DESC")
            ->indexBy('id')
            ->all();

        \Yii::$app->data->models = TaskResults::arrayAttributes($models, [], [], true);
        \Yii::$app->data->task = Tasks::arrayAttributes($task, [], [], true);

        return $this->render("check", [
            "task" => $task,
            "models" => $models
        ]);
    }

    public function actionEstimate()
    {
        if (\Yii::$app->request->get('id')) {
            $model = TaskResults::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if (!$model) throw new \yii\db\Exception("NO MODEL");

        if (\Yii::$app->request->post('TaskResults')) {

            $model->attributes = \Yii::$app->request->post('TaskResults');

            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Оценка сохранена"));
                return $this->renderJSON([
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->model = Tasks::arrayAttributes($model, [], array_merge((new TaskResults())->attributes()), true);

        return $this->render("estimate", [
            "model" => $model
        ]);

    }

    public function actionOptions()
    {

        $task = Tasks::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$task) throw new NotAcceptableHttpException();
        if (!\Yii::$app->request->get("from")) throw new NotAcceptableHttpException("NO");

        $relationModel = From::instance()->getAssignedRelation($task->id, 'task');
        if (!$relationModel) throw new NotAcceptableHttpException();
        if (\Yii::$app->request->post("TaskOptions")) {
            $relationModel->attributes = \Yii::$app->request->post("TaskOptions");
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
            'task' => $task,
            'model' => $relationModel,
            'criteria_templates' => $criteria_templates
        ]);

    }

    public function actionDiscard()
    {

        if (\Yii::$app->request->get("id")) {
            $model = TaskResults::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        }

        if (!$model) throw new NotAcceptableHttpException("NO RESULT");

        if (\Yii::$app->request->post("TaskResults"))
        {
            $model->status_note = \Yii::$app->request->post("TaskResults")['status_note'];
            $model->status = TaskResults::STATUS_DISCARDED;

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

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['teacher'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['assigned', 'begin','process', 'finish'],
                        'roles' => ['pupil'],
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