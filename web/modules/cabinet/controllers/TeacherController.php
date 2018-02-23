<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 07.08.2017
 * Time: 17:14
 */

namespace app\modules\cabinet\controllers;


use app\components\Controller;
use app\helpers\ArrayHelper;
use app\models\Events;
use app\models\results\TestResults;
use common\models\bilimal\Lesson;
use common\models\bilimal\PersonDivisionLink;
use yii\db\Expression;
use yii\filters\AccessControl;

class TeacherController extends Controller
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

        $last_events->andWhere([
            'events.user_id' => \Yii::$app->user->id
        ]);

        $last_events->andWhere("events.end_ts > :now", [
            ":now" => new Expression("NOW")
        ]);

        $last_events->limit = 5;
        $last_events->orderBy(['begin_ts' => SORT_ASC]);
        $last_events = $last_events->all();

        if (\Yii::$app->user->identity->currentOrganizationRole == "bilimal_teacher") {

            $division_links = PersonDivisionLink::find()->andWhere([
                "person_id" => \Yii::$app->user->identity->bilimalUser->bilimal_user_id,
                "person_type" => "teacher"
            ])->all();

            if ($division_links) {

                $lessons = Lesson::find()
                    ->indexBy("id")
                    ->andWhere(['in', 'division_id', ArrayHelper::map($division_links, 'division_id', 'division_id')])
                    ->andWhere(['>', 'test_id', 0])
                    ->orderBy("date DESC")
                    ->all();

                $results = TestResults::find()
                    ->with(['user'])
                    ->andWhere(['in', 'source_id', ArrayHelper::map($lessons, 'id', 'id')])
                    ->andWhere(['source_table' => "bilimal_lesson"])
                    ->orderBy("result DESC")
                    ->all();

            }

        }

        return $this->render("index", [
            "last_events" => $last_events,
            "lessons" => $lessons,
            "results" => $results
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
                        'roles' => ['base_teacher'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?','@']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }

}