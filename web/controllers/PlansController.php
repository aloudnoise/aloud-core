<?php

namespace app\controllers;
use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\models\Events;
use app\models\filters\EventsFilter;
use app\models\filters\PlansFilter;
use app\models\relations\EventUser;
use app\models\results\TestResults;
use app\traits\BackboneRequestTrait;
use common\helpers\Common;
use common\models\EducationPlans;
use common\models\forms\BaseFilterForm;
use common\models\From;
use common\models\Tags;
use yii\db\Exception;
use yii\db\Expression;
use yii\filters\AccessControl;

/**
 * Class EventsController
 * @package app\controllers
 */
class PlansController extends Controller {

    public function beforeAction($action) {

        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), OrganizationUrl::to(["/main/index"]));
        return $p;
    }

    public function actionIndex()
    {

        $filter = $this->_getFilter();

        return $this->render("index", [
            "filter" => $filter,
        ]);

    }

    public function actionList()
    {
        $filter = $this->_getFilter();
        $plans = $this->_getPlans();

        $filter->applyFilter($plans);

        $start_date = (new \DateTime())->setTimestamp(mktime(0,0,0,$filter['month'] ?: 1,1,$filter['year']));
        $end_date = (new \DateTime())->setTimestamp(mktime(23,59,59,$filter['month'] ?: 12,31,$filter['year']));

        $plans->andWhere("(:ts_start, :ts_end) OVERLAPS (education_plans.begin_ts, education_plans.end_ts)", [
            ":ts_start" => $start_date->format('d.m.Y H:i:s'),
            ":ts_end" => $end_date->format('d.m.Y H:i:s')
        ]);

        $plans->orderBy("education_plans.begin_ts DESC");
        $plans = $plans->all();

        \Yii::$app->data->events = BackboneRequestTrait::arrayAttributes($plans, [], [], true);

        return $this->render("list", [
            'plans' => $plans,
            'filter' => $filter
        ]);

    }

    public function actionView()
    {

        $model = EducationPlans::find()
            ->notDeleted()
            ->byOrganization()
            ->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new \Exception("NO PLAN PROVIDED");

        if (\Yii::$app->request->post("EducationPlans")) {
            $model->attributes = \Yii::$app->request->post("EducationPlans");
            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Учебный план успешно сохранен"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/plans/view", "id" => $model->id]),
                    "target" => "normal"
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['events_planned', 'pupils_planned'], true);

        return $this->render("view", [
            "model" => $model
        ]);

    }

    public function actionAdd()
    {

        $filter = $this->_getFilter();

        if (\Yii::$app->request->get('id')) {
            $model = EducationPlans::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        }else {
            $model = new EducationPlans();
        }


        if (\Yii::$app->request->post('EducationPlans')) {
            $model->attributes = \Yii::$app->request->post('EducationPlans');
            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Учебный план успешно сохранен"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/plans/view", "id" => $model->id]),
                    "target" => "normal"
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        \Yii::$app->data->minDate = (new \DateTime())->format('d.m.Y');
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], array_merge((new EducationPlans())->attributes(), ['description']), true);
        return $this->render("form", [
            "model" => $model,
            "filter" => $filter
        ]);
    }

    public function actionDelete()
    {

        $plan = EducationPlans::find()->byPk(\Yii::$app->request->get('id'))->one();
        if (!$plan) {
            throw new \Exception("No such event");
        }

        if (!$plan->canEdit) {
            throw new \Exception("You dont have rights to do this");
        }

        $plan->delete();

        \Yii::$app->response->redirect(OrganizationUrl::to(['/plans/index']), [
            'scroll' => false
        ]);
    }

    public function actionCalendar()
    {
        $filter = $this->_getFilter();
        $events = $this->_getPlans();

        $filter->applyFilter($events);

        $start_date = (new \DateTime())->setTimestamp(mktime(0,0,0,$filter['month'] ?: 1,1,$filter['year']));
        $end_date = (new \DateTime())->setTimestamp(mktime(23,59,59,$filter['month'] ?: 12,31,$filter['year']));

        $dates = $events->select([
            'start_m' => new Expression("date_part('month', education_plans.begin_ts)"),
            'start_y' => new Expression("date_part('year', education_plans.begin_ts)"),
            'months' => new Expression("date_part('month', age(education_plans.end_ts, education_plans.begin_ts))")
        ])->andWhere("(:ts_start, :ts_end) OVERLAPS (education_plans.begin_ts, education_plans.end_ts)", [
            ":ts_start" => $start_date->format('d.m.Y H:i:s'),
            ":ts_end" => $end_date->format('d.m.Y H:i:s')
        ])->asArray()->all();

        $calendar_plans = [];
        if (!empty($dates)) {
            foreach ($dates as $d) {
                for ($i = $d['start_m']; $i <= $d['start_m']+$d['months']; $i++) {
                    $ts = mktime(0, 0, 0, $i, 1, $d['start_y']);
                    $calendar_plans[$ts] = $calendar_plans[$ts] ? $calendar_plans[$ts]+1 : 1;
                }
            }
        }

        return $this->render("calendar", [
            'filter' => $filter,
            'plans' => $calendar_plans
        ]);

    }

    public function _getFilter()
    {

        $filter = new PlansFilter();
        $filter->attributes = array_merge([
            'year' => date('Y')
        ], \Yii::$app->request->get("filter", []));

        return $filter;
    }

    public function _getPlans()
    {
        $events = EducationPlans::find()->byOrganization();
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
                        'roles' => ['specialist'],
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