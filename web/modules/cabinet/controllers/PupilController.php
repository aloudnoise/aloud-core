<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 11.08.2017
 * Time: 2:51
 */

namespace app\modules\cabinet\controllers;


use app\components\Controller;
use app\helpers\ArrayHelper;
use app\models\Events;
use app\models\Materials;
use app\models\relations\EventUser;
use common\models\bilimal\Lesson;
use common\models\bilimal\PersonDivisionLink;
use yii\db\Expression;

class PupilController extends Controller
{

    public function actionIndex()
    {

        $last_events = Events::find()->byOrganization();
        $last_events->notDeleted();

        $last_events->joinWith([
            "courses" => function($cq) {
                return $cq->with([
                    "course"
                ]);
            },
            "materials" => function($mq) {
                return $mq->with([
                    "material"
                ]);
            },
            "tests" => function($tq) {
                return $tq->with([
                    "test"
                ]);
            }
        ]);

        $last_events->joinWith([
            "member" => function($query) {
                return $query->andOnCondition("event_user.state != ".EventUser::STATE_FINISHED);
            }
        ]);
        $last_events->andWhere("(event_user.id IS NOT NULL OR events.state = :s)", [
            ":s" => Events::STATE_SHARED
        ]);

        $last_events->andWhere("events.begin_ts <= :t1 AND events.end_ts >= :t2", [
            ":t1" => (new \DateTime())->format("d.m.Y"),
            ":t2" => (new \DateTime())->format("d.m.Y")
        ]);

        $last_events->limit = 5;
        $last_events->orderBy(['begin_ts' => SORT_ASC]);
        $last_events = $last_events->all();

        $last_materials = Materials::find()->byOrganization()
            ->notDeleted()
            ->orderBy("ts DESC")
            ->limit(15)
        ->all();

        return $this->render("index", [
            "last_events" => $last_events,
            "lessons" => $lessons,
            "last_materials" => $last_materials
        ]);
    }

}