<?php

namespace app\controllers;

use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\models\News;
use common\models\forms\BaseFilterForm;
use common\models\forms\FilterForm;
use common\models\Tags;
use yii\filters\AccessControl;

/**
 * Class NewsController
 * @package app\controllers
 */
class NewsController extends Controller
{

    public function beforeAction($action)
    {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main", "Главная"), OrganizationUrl::to(["/main/index"]));
        return $p;
    }

    public function actionIndex()
    {
        $tags = Tags::find()
            ->distinct()
            ->byOrganization()
            ->select(['name'])
            ->andWhere([
                'table' => 'news'
            ])
            ->asArray()
            ->column();

        return $this->render("index", [
            "tags" => $tags,
        ]);
    }

    public function actionList()
    {
        $news = News::find()
            ->byOrganization()
            ->orderBy("news.ts DESC");

        $filter = new BaseFilterForm();

        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }
        $filter->applyFilter($news);

        $news = $news->all();

        return $this->render("list", [
            'news' => $news,
            "filter" => $filter,
        ]);
    }

    public function actionView()
    {
        $model = News::find()
            ->byOrganization()
            ->notDeleted()
            ->byPk(\Yii::$app->request->get("id"))->one();

        if (!$model) throw new \Exception("NO NEW PROVIDED");

        if (!$model->canView) {
            throw new \Exception("NOT ALLOWED");
        }

        $model->addView();

        return $this->render("view", [
            "model" => $model,
        ]);
    }

    public function actionAdd()
    {
        if (\Yii::$app->request->get('id')) {
            $model = News::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        } else {
            $model = new News();
        }

        if (\Yii::$app->request->post('News')) {
            $model->attributes = \Yii::$app->request->post('News');
            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok", \Yii::t("main","Новость успешно добавлена/изменена"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/news/view", "id" => $model->id]),
                    "target" => "normal"
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'news'
        ])->asArray()->column();

        \Yii::$app->data->tags = $tags;
        \Yii::$app->data->model = News::arrayAttributes($model, [], array_merge((new News())->attributes(), ['tagsString']), true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();

        return $this->render("form", [
            "model" => $model,
            "tags" => $tags,
        ]);
    }

    public function actionDelete()
    {
        $model = News::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        if (!$model) {
            throw new \Exception("No such new");
        }

        if (!$model->canEdit) {
            throw new \Exception("You don't have rights to do this");
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash("ok",\Yii::t("main","Новость удалена"));
        } else {
            \Yii::$app->session->setFlash("error",\Yii::t("main","Ошибка удаления"));
        }

        \Yii::$app->response->redirect(OrganizationUrl::to(["/news/index"]));
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['base_pupil'],
                        'actions' => ['index', 'view', 'list']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['specialist'],
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