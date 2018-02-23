<?php
namespace app\modules\cabinet\controllers;

use app\components\Controller;
use app\models\Events;
use app\models\results\TestResults;
use app\models\Tests;
use app\models\Users;
use common\models\EducationPlans;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use common\models\Organizations;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        $id = Organizations::getCurrentOrganizationId();
        if (!$id AND \Yii::$app->user->can("SUPER")) {
            foreach (\Yii::$app->user->identity->organizationsList as $org) {
                $data[$org->id] = self::getOrganizationData($org->id);
            }
        } else {
            $data[$id] = self::getOrganizationData($id);
        }
        return $this->render("index.php", [
            'data' => $data
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
                        'roles' => ['admin'],
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

    /**
     * Получаем  данные организации
     *
     * @param int $id Идентификатор организации
     * @return array
     */
    private static function getOrganizationData($id)
    {
        $data['name'] = Organizations::findOne($id)->name;
        $orgData['tests'] = Tests::find()
            ->byOrganization($id)
            ->count();
        $orgData['events'] = Events::find()
            ->byOrganization($id)
            ->count();

        $plan = EducationPlans::find()
            ->byOrganization($id)
            ->andWhere(':date BETWEEN education_plans.begin_ts AND education_plans.end_ts', [
                ':date' => date('d.m.Y')
            ])->one();

        $orgData['events_planned'] = ($plan AND $plan->events_planned) ? $plan->events_planned : 'Не выставлено';

        $orgData['test_results'] = TestResults::find()
            ->byOrganization($id)
            ->andWhere(['<>', 'from', 'testing'])
            ->count();

        $data['counters'] = new ArrayDataProvider([
            'allModels' => [$orgData],
        ]);

        $teachers = Users::find()
            ->select([
                'users.fio',
                'tests_count' => 'count(DISTINCT (tests.*))',
                'events_count' => 'count(DISTINCT (events.*))',
                'results_count' => 'count(DISTINCT (test_results.*))',
            ])
            ->innerJoinWith([
                'organizations' => function ($query) use ($id) {
                    $query->andWhere([
                        'target_id' => $id,
                    ])->andWhere([
                        'in', 'user_organization.role', ['teacher','admin']
                    ]);
                }
            ])
            ->joinWith([
                'tests.results',
                'events',
            ])
            ->groupBy('fio')
            ->asArray();
        $data['teachers'] = new ActiveDataProvider([
            'query' => $teachers,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);
        return $data;
    }

}