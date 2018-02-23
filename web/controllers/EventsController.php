<?php

namespace app\controllers;
use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\models\Events;
use app\models\filters\EventsFilter;
use app\models\relations\EventUser;
use app\models\results\TestResults;
use common\helpers\Common;
use common\models\forms\BaseFilterForm;
use common\models\From;
use common\models\Notifications;
use common\models\notifications\EventNotification;
use common\models\Organizations;
use common\models\Tags;
use yii\db\Exception;
use yii\db\Expression;
use yii\filters\AccessControl;

/**
 * Class EventsController
 * @package app\controllers
 */
class EventsController extends Controller {

    public function beforeAction($action) {

        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), OrganizationUrl::to(["/main/index"]));
        return $p;
    }

    public function actionIndex()
    {

        $filter = $this->_getFilter();

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'events'
        ])->asArray()->column();

        return $this->render("index", [
            "tags" => $tags,
            "filter" => $filter,
        ]);

    }

    public function actionList()
    {
        $filter = $this->_getFilter();
        $events = $this->_getEvents();

        $filter->applyFilter($events);

        $start_date = (new \DateTime())->setTimestamp(mktime(0,0,0,$filter['month'], Common::calendar_1day($filter['month'],$filter['year']) , $filter['year']));
        $end_date = (new \DateTime())->setTimestamp(mktime(23,59,59,$filter['month'], Common::calendar_lday($filter['month'],$filter['year']) , $filter['year']));

        if (!$filter['day']) {
            $events->andWhere("(:ts_start, :ts_end) OVERLAPS (events.begin_ts, events.end_ts)", [
                ":ts_start" => $start_date->format('d.m.Y H:i:s'),
                ":ts_end" => $end_date->format('d.m.Y H:i:s')
            ]);
        } else {
            $events->andWhere(':date BETWEEN events.begin_ts AND events.end_ts', [
                ':date' => $filter['day'].".".$filter['month'].".".$filter['year']
            ]);
        }

        $events->orderBy("events.begin_ts DESC");
        $events = $events->all();

        \Yii::$app->data->events = Events::arrayAttributes($events, [], [], true);

        return $this->render("list", [
            'events' => $events,
            'filter' => $filter
        ]);

    }

    public function actionView()
    {

        $model = Events::find()
            ->notDeleted()
            ->byOrganization()
            ->with([
                "users" => function($uq) {
                    return $uq->with([
                        "user",
                    ])->orderBy("ts DESC");
                },
                "courses" => function($cq) {
                    return $cq->with([
                        "course"
                    ])->orderBy("ts DESC");
                },
                "materials" => function($mq) {
                    return $mq->with([
                        "material"
                    ])->orderBy("ts DESC");
                },
                "tests" => function($tq) {
                    return $tq->with([
                        "test"
                    ])->orderBy("ts DESC");
                },
                "tasks" => function($tq) {
                    return $tq->with([
                        "task"
                    ])->orderBy("ts DESC");
                },
                "themes" => function($tq) {
                    return $tq->orderBy("ts DESC");
                },
                'organizations' => function($oq) {
                    return $oq->orderBy("ts DESC");
                }
            ])
            ->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new \Exception("NO EVENT PROVIDED");

        if (!$model->canView) {
            throw new \Exception("NOT ALLOWED");
        }

        $from = new From(['event', $model->id, 'assign']);

        return $this->render("view", [
            "model" => $model,
            'from' => $from
        ]);

    }

    public function actionShare()
    {

        $model = Events::find()
            ->byOrganization()
            ->with([
                "users" => function($uq) {
                    return $uq->with([
                        "user",
                    ]);
                }
            ])
            ->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new \Exception("NO EVENT PROVIDED");
        if (!empty($model->users)) throw new \Exception("MUST BE NO USERS");
        if (!$model->canEdit) {
            throw new \Exception("NOT ALLOWED");
        }

        $model->state = $model->state == Events::STATE_SHARED ? 1 : Events::STATE_SHARED;
        if ($model->save()) {

            $notification = new EventNotification();
            $notification->target_id = $model->id;
            $notification->target_type = Notifications::TYPE_EVENT;
            $notification->organization_id = Organizations::getCurrentOrganizationId();
            $notification->action = $model->state == Events::STATE_SHARED ? EventNotification::ACTION_SHARED : EventNotification::ACTION_UNSHARED;
            $notification->send();

        }
        \Yii::$app->response->redirect(OrganizationUrl::to(["/events/view", "id" => $model->id]));

    }

    public function actionFinish()
    {

        $model = Events::find()
            ->byOrganization()
            ->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new \Exception("NO EVENT PROVIDED");

        if (!$model->isParticipant) {
            throw new \Exception("NOT ALLOWED");
        }

        $model->member->state = EventUser::STATE_FINISHED;
        if (!$model->member->save()) {
        }
        \Yii::$app->response->redirect(OrganizationUrl::to(["/events/view", "id" => $model->id]));

    }

    public function actionAdd()
    {

        $filter = $this->_getFilter();

        if (\Yii::$app->request->get('id')) {
            $model = Events::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        }else {
            $model = new Events();
        }


        if (\Yii::$app->request->post('Events')) {
            $model->attributes = \Yii::$app->request->post('Events');
            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Мероприятие успешно сохранено"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/events/view", "id" => $model->id]),
                    "target" => "normal"
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'events'
        ])->asArray()->column();
        \Yii::$app->data->tags = $tags;
        \Yii::$app->data->minDate = (new \DateTime())->format('d.m.Y');
        \Yii::$app->data->model = Events::arrayAttributes($model, [],  ['id', 'name', 'description', 'begin_ts', 'end_ts', 'education_view', 'tagsString'], true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();
        return $this->render("form", [
            "model" => $model,
            "tags" => $tags,
            "filter" => $filter
        ]);
    }

    public function actionDelete()
    {

        $event = Events::find()->byPk(\Yii::$app->request->get('eid'))->one();
        if (!$event) {
            throw new \Exception("No such event");
        }

        if (!$event->canEdit) {
            throw new \Exception("You dont have rights to do this");
        }

        $acts = array(
            1 => array(
                "url"=>OrganizationUrl::to(["/events/index"]),
                "model"=>'\app\models\Events'
            ),
            2 => array(
                "url"=>OrganizationUrl::to(["/events/view", "id"=>$event->id]),
                "model"=>'\app\models\relations\EventUser'
            ),
            3 => array(
                "url"=>OrganizationUrl::to(["/events/view", "id"=>$event->id]),
                "model"=>'\app\models\relations\EventCourse'
            ),
            4 => array(
                "url"=>OrganizationUrl::to(["/events/view", "id"=>$event->id]),
                "model"=>'\app\models\relations\EventTest'
            ),
            5 => array(
                "url"=>OrganizationUrl::to(["/events/view", "id"=>$event->id]),
                "model"=>'\app\models\relations\EventMaterial'
            ),
            7 => array(
                "url" => OrganizationUrl::to(['/events/view', 'id' => $event->id]),
                'model' => '\app\models\relations\EventTask'
            ),
            8 => array(
                "url" => OrganizationUrl::to(['/events/view', 'id' => $event->id]),
                'model' => '\app\models\relations\EventTheme'
            ),
            9 => array(
                "url" => OrganizationUrl::to(['/events/view', 'id' => $event->id]),
                'model' => '\app\models\relations\EventOrganization'
            )
        );

        $model = $acts[\Yii::$app->request->get("type")]['model'];
        if ((new $model())->deleteAccess(["id"=>\Yii::$app->request->get("tid")])) {
            if ((new $model())->deleteRequest(["id"=>\Yii::$app->request->get("tid")])) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Удаление прошло успешно"));
            }
        }
        \Yii::$app->response->redirect($acts[\Yii::$app->request->get("type")]['url'], [
            'scroll' => false
        ]);
    }

    public function actionCalendar()
    {
        $filter = $this->_getFilter();
        $events = $this->_getEvents();

        $filter->applyFilter($events);

        $start_date = (new \DateTime())->setTimestamp(mktime(0,0,0,$filter['month'], Common::calendar_1day($filter['month'],$filter['year']) , $filter['year']));
        $end_date = (new \DateTime())->setTimestamp(mktime(23,59,59,$filter['month'], Common::calendar_lday($filter['month'],$filter['year']) , $filter['year']));



        $dates = $events->select([
            'start_d' => new Expression("date_part('day', events.begin_ts)"),
            'start_m' => new Expression("date_part('month', events.begin_ts)"),
            'start_y' => new Expression("date_part('year', events.begin_ts)"),
            'days' => new Expression("date_part('day', age(events.end_ts, events.begin_ts))")
        ])->andWhere("(:ts_start, :ts_end) OVERLAPS (events.begin_ts, events.end_ts)", [
            ":ts_start" => $start_date->format('d.m.Y H:i:s'),
            ":ts_end" => $end_date->format('d.m.Y H:i:s')
        ])->asArray()->all();

        $calendar_events = [];
        if (!empty($dates)) {
            foreach ($dates as $d) {
                for ($i = $d['start_d']; $i <= $d['start_d']+$d['days']; $i++) {
                    $ts = mktime(0, 0, 0, $d['start_m'], $i, $d['start_y']);
                    $calendar_events[$ts] = $calendar_events[$ts] ? $calendar_events[$ts]+1 : 1;
                }
            }
        }

        return $this->render("calendar", [
            'filter' => $filter,
            'events' => $calendar_events
        ]);

    }

    public function _getFilter()
    {

        $filter = new EventsFilter();
        $filter->attributes = array_merge([
            'month' => date('n'),
            'year' => date('Y')
        ], \Yii::$app->request->get("filter", []));

        return $filter;
    }

    public function _getEvents()
    {

        $events = Events::find()->byOrganization();
        $events->with([
            'materials','tests','courses','tasks','themes','tags'
        ]);
        $events->notDeleted();

        if (\Yii::$app->user->can("admin")) {

        } else if (\Yii::$app->user->can("base_teacher")) {
            $events->byUser();
        } else if (\Yii::$app->user->can("base_pupil")) {
            $events->joinWith([
                "member"
            ]);
            $events->andWhere("(event_user.id IS NOT NULL OR events.state = :s)", [
                ":s" => Events::STATE_SHARED
            ]);
        }

        return $events;

    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index', 'list', 'calendar', 'view', 'finish']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['base_teacher', 'head'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?', '@']
                    ]
                ],
            ],
        ]);
    }



}
?>