<?php
namespace app\modules\hr\controllers;

use app\components\Controller;
use app\components\VarDumper;
use app\helpers\OrganizationUrl;
use app\models\Courses;
use app\models\DicValues;
use app\models\filters\UsersFilter;
use app\models\forms\UserForm;
use app\models\forms\UsersImportForm;
use app\models\Materials;
use app\models\results\TestResults;
use app\models\Users;
use app\traits\BackboneRequestTrait;
use common\models\counters\Downloads;
use common\models\counters\Views;
use common\models\forms\HtmlRender;
use common\models\forms\PhpWordHtmlRender;
use common\models\From;
use common\models\relations\UserOrganization;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotAcceptableHttpException;

/**
 * Class MainController
 * @package app\controllers
 */
class UsersController extends Controller
{

    public function beforeAction($action)
    {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main", "Главная"), OrganizationUrl::to(["/main/index"]));
        return $p;
    }

    public function actionIndex()
    {

        $filter = new UsersFilter();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (\Yii::$container->get('common\models\relations\UserOrganization'))::find()->byOrganization()
            ->joinWith([
                'user' => function($q) {
                    return $q->with(['credentials']);
                }
            ]);

        $filter->applyFilter($query);

        $query->orderBy([
            'users.ts' => SORT_DESC,
            'users.fio' => SORT_ASC
        ]);


        if (\Yii::$app->request->get("export")) {
            $export = new HtmlRender();
            $export->orientation = 'landscape';

            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
            ]);

            $export->html = $this->renderPartial("pupils", [
                'filter' => $filter,
                'provider' => $provider,
                'export' => true
            ]);

            $export->file_name = \Yii::t("main","Экспорт слушателей от {date}", [
                'date' => date('d.m.Y')
            ]);

            $export->render();

        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);


        return $this->render("index", [
            "provider" => $provider,
            "filter" => $filter,
        ]);
    }

    public function actionSearch()
    {

        $filter = new UsersFilter();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        if ($filter->search) {

            $query = UserOrganization::find()
                ->joinWith(['user'])
                ->byOrganization();
            if ($filter->search) {
                $query->andFilterWhere(['like', 'LOWER(users.fio)', mb_strtolower($filter->search, "UTF-8")]);
            }

            $query->andWhere([
                '!=', 'related_id', \Yii::$app->user->id
            ]);

            $query->orderBy(['users.fio' => SORT_ASC]);

            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 30
                ]
            ]);


        }

        \Yii::$app->data->filter = $filter->toArray();

        return $this->render("search", [
            "provider" => $provider ?: null,
            "filter" => $filter
        ]);
    }

    public function actionAssign()
    {

        $filter = new UsersFilter();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (\Yii::$container->get('common\models\relations\UserOrganization'))::find()->byOrganization()
            ->joinWith([
                'user'
            ]);

        if (\Yii::$app->request->get("from")) {
            $relation = From::instance()->getAssignedRelationClass('user');
            $filter->exclude_ids = $relation::find()->select(['related_id'])->andWhere([
                'target_id' => From::instance()->id
            ])->asArray()->column();
        }

        $filter->applyFilter($query);

        $query->orderBy([
            'users.ts' => SORT_DESC,
            'users.fio' => SORT_ASC
        ]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        return $this->render("assign", [
            "provider" => $provider,
            "filter" => $filter,
        ]);
    }

    public function actionAdd()
    {

        $model = new UserForm();
        if (\Yii::$app->request->get("id")) {
            $model->loadData(\Yii::$app->request->get("id"));
        } else {
            if (\Yii::$app->request->get("type") == UsersFilter::TYPE_PUPILS) {
                $model->role = 'pupil';
            }

            if (\Yii::$app->request->get("existed")) {
                $model->existed = 1;
            }

        }

        if (\Yii::$app->request->post("UserForm")) {

            $model->attributes = \Yii::$app->request->post("UserForm");
            if ($model->save()) {
                return $this->renderJSON([
                    'redirect' => OrganizationUrl::to(['/hr/users/index', "type" => $model->role == "pupil" ? UsersFilter::TYPE_PUPILS : UsersFilter::TYPE_STAFF])
                ]);
            }
            return $this->renderJSON($model->getErrors(), true);

        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['phone','email','login','fio','role','password'], true);

        return $this->render("add", [
            'model' => $model,
        ]);

    }

    public function actionDelete()
    {

        $user_org = UserOrganization::find()->byPk(\Yii::$app->request->get("id"))->byOrganization()->one();

        if ($user_org->delete()) {
            \Yii::$app->session->setFlash("ok", \Yii::t("main","Запись удалена"));
        }

        return \Yii::$app->response->redirect(OrganizationUrl::to(['/hr/users/index']));


    }

    public function actionOptions()
    {

        $user = Users::find()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$user) throw new NotAcceptableHttpException();
        if (!\Yii::$app->request->get("from")) throw new NotAcceptableHttpException("NO");

        $relationModel = From::instance()->getAssignedRelation($user->id, 'user');
        if (!$relationModel) throw new NotAcceptableHttpException();
        if (\Yii::$app->request->post("UserOptions")) {
            $relationModel->attributes = \Yii::$app->request->post("UserOptions");
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

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($relationModel, [], ['criteria'], true);

        return $this->render("options", [
            'user' => $user,
            'model' => $relationModel,
            'criteria_templates' => $criteria_templates
        ]);

    }

    public function actionProfile()
    {

        $model = Users::find()->byPk(\Yii::$app->request->get("profile_id"))->one();
        if (!\Yii::$app->user->can("base_teacher")) {
            if ($model->id != \Yii::$app->user->id) \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        if ($model->canEdit AND \Yii::$app->request->post('Users')) {
            $model->attributes = \Yii::$app->request->post('Users');

            if ($model->save()) {
                \Yii::$app->session->setFlash('ok', \Yii::t('main', 'Профиль успешно изменен'));
                return $this->renderJSON([
                    'redirect' => OrganizationUrl::to(['/hr/users/profile', 'profile_id' => $model->id])
                ]);
            }
            return $this->renderJSON($model->getErrors(), true);
        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['photo','fio'], true);

        $test_results = TestResults::find()->byOrganization()->andWhere([
            'user_id' => $model->id
        ])->with(['test'])->orderBy('test_results.ts DESC')->all();

        $courses_ids = Views::find()
            ->distinct()
            ->select(['record_id'])
            ->andWhere([
                'user_id' => $model->id,
                'table' => 'courses'
            ])
            ->asArray()->column();

        $courses = Courses::find()->byOrganization()->andWhere([
            'in', 'id', $courses_ids
        ])->all();

        $materials_ids = Views::find()
            ->distinct()
            ->select(['record_id'])
            ->andWhere([
                'user_id' => $model->id,
                'table' => 'materials'
            ])
            ->asArray()->column();

        $materials = Materials::find()->byOrganization()->andWhere([
            'in', 'id', $materials_ids
        ])->all();

        $d_materials_ids = Downloads::find()
            ->distinct()
            ->select(['record_id'])
            ->andWhere([
                'user_id' => $model->id,
                'table' => 'materials'
            ])
            ->asArray()->column();

        $d_materials = Materials::find()->byOrganization()->andWhere([
            'in', 'id', $d_materials_ids
        ])->all();

        \Yii::$app->data->size = 'lg';

        return $this->render("profile", [
            'model' => $model,
            'courses' => $courses,
            'materials' => $materials,
            'd_materials' => $d_materials,
            'test_results' => $test_results
        ]);

    }

    public function actionImport()
    {

        $model = new UsersImportForm();
        if (\Yii::$app->request->get("document")) {
            $model->document = \Yii::$app->request->get("document");
        }

        $step = \Yii::$app->request->get("step", 1);

        if (\Yii::$app->request->post("UsersImportForm")) {

            $model->attributes = \Yii::$app->request->post("UsersImportForm");
            if ($model->save()) {
                return $this->renderJSON([
                    'redirect' => OrganizationUrl::to(['/hr/users/index', 'filter' => [
                            'type' => UsersFilter::TYPE_PUPILS,
                            'ids' => implode(",", $model->generated_ids)
                        ]
                    ])
                ]);
            }
            return $this->renderJSON($model->getErrors(), true);

        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['rows','columns','division','group'], true);

        $steps = [
            1 => "import_step1",
            2 => "import_step2"
        ];

        return $this->render($steps[$step], [
            "model" => $model
        ]);
    }
//
//    public function actionSend()
//    {
//        if (\Yii::$app->request->get("id")) {
//            $user = Users::find()->byPk(\Yii::$app->request->get("id"))->one();
//        }
//
//        // Отправялем письмо
//        try {
//            $isSend = \Yii::$app->mailer->compose('webinar', [
//                'login' => $user->credentials['login']->credential,
//            ])
//                ->setFrom(\Yii::$app->params['ROBOT_EMAIL'])
//                ->setTo($user->credentials['email']->credential)
//                ->setSubject(\Yii::t('main', 'Доступ к вебинару для фокус группы на 16.02.2018 в 16:00'))
//                ->send();
//        } catch (\Exception $exception) {
//            var_dump($user->credentials['email']);
//            var_dump($exception->getMessage());
//            return false;
//        }
//    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => [
                            'profile','search'
                        ]
                    ],
                    [
                        'allow' => true,
                        'roles' => ['base_teacher'],
                        'actions' => [
                            'assign','search','options'
                        ]
                    ],
                    [
                        'allow' => true,
                        'roles' => ['SUPER', 'specialist'],
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