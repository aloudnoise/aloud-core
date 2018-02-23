<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 10.08.2017
 * Time: 18:10
 */

namespace app\modules\hr\controllers;


use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\models\filters\GroupsFilter;
use app\models\forms\GroupsModelForm;
use app\models\Groups;
use app\traits\BackboneRequestTrait;
use common\components\ActiveRecord;
use common\models\forms\FilterForm;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotAcceptableHttpException;

class GroupsController extends Controller
{

    public static $modelClass = 'app\models\forms\GroupsModelForm';

    public function beforeAction($action)
    {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main", "Главная"), OrganizationUrl::to(["/main/index"]));
        return $p;
    }

    public function actionIndex()
    {

        $filter = new GroupsFilter();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (\Yii::$container->get('app\models\Groups'))::find()->byOrganization()
            ->with([
                'users'
            ]);

        $filter->applyFilter($query);

        $query->orderBy([
            'groups.ts' => SORT_DESC,
        ]);

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

        $groupModel = (static::$modelClass)::getModel();

        $filter = \Yii::createObject(GroupsFilter::className());
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        if ($filter->search) {

            $query = $groupModel::find()->byOrganization();
            $filter->applyFilter($query);

            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 30
                ]
            ]);
        }

        \Yii::$app->data->filter = $filter->toArray();

        return $this->render("search", [
            "provider" => isset($provider) ? $provider : null,
            "filter" => $filter
        ]);
    }

    public function actionAdd()
    {

        $model = new Groups();
        if (\Yii::$app->request->get("id")) {
            $model = Groups::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        }

        if (!$model->canEdit) {
            throw new MethodNotAllowedHttpException("NO");
        }

        if (\Yii::$app->request->post("Groups")) {

            $model->attributes = \Yii::$app->request->post("Groups");
            if ($model->save()) {
                return $this->renderJSON([
                    'redirect' => OrganizationUrl::to(['/hr/groups/view', 'id' => $model->id])
                ]);
            }
            return $this->renderJSON($model->getErrors(), true);

        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['name', 'description'], true);

        return $this->render("form", [
            'model' => $model,
        ]);

    }

    public function actionView()
    {
        $model = Groups::find()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new NotAcceptableHttpException("NO MODEL");

        return $this->render("view", [
            'model' => $model,
        ]);
    }

    public function actionDelete()
    {

        $group = Groups::find()->byPk(\Yii::$app->request->get("id"))->byOrganization()->one();

        if ($group->delete()) {
            \Yii::$app->session->setFlash("ok", \Yii::t("main","Запись удалена"));
        }

        return \Yii::$app->response->redirect(OrganizationUrl::to(['/hr/groups/index']));


    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['base_teacher'],
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