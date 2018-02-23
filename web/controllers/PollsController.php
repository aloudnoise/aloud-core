<?php

namespace app\controllers;

use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\models\News;
use app\models\Polls;
use app\models\results\PollResults;
use app\traits\BackboneRequestTrait;
use common\models\forms\BaseFilterForm;
use common\models\forms\FilterForm;
use common\models\Tags;
use yii\filters\AccessControl;

/**
 * @package app\controllers
 */
class PollsController extends Controller
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
                'table' => 'polls'
            ])
            ->asArray()
            ->column();

        return $this->render("index", [
            "tags" => $tags,
        ]);
    }

    public function actionList()
    {
        $polls = Polls::find()
            ->byOrganization()
            ->notDeleted()
            ->orderBy("polls.ts DESC");

        $filter = new BaseFilterForm();

        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }
        $filter->applyFilter($polls);

        $polls = $polls->all();

        return $this->render("list", [
            'polls' => $polls,
            "filter" => $filter,
        ]);
    }

    public function actionAdd()
    {
        if (\Yii::$app->request->get('id')) {
            $model = Polls::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        } else {
            $model = new Polls();
        }

        if (\Yii::$app->request->post('Polls')) {
            $model->attributes = \Yii::$app->request->post('Polls');
            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok", \Yii::t("main","Голосование успешно добавлено/изменено"));
                return $this->renderJSON([
                    "redirect"=>OrganizationUrl::to(["/polls/index"]),
                    "target" => "normal"
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }
        }

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'polls'
        ])->asArray()->column();

        \Yii::$app->data->tags = $tags;
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], array_merge((new Polls())->attributes(), ['tagsString']), true);

        return $this->render("form", [
            "model" => $model,
            "tags" => $tags,
        ]);
    }

    public function actionToggle()
    {
        $model = Polls::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        if (!$model) {
            throw new \Exception("No such poll");
        }

        if (!$model->canEdit) {
            throw new \Exception("You don't have rights to do this");
        }

        $model->status = $model->status == Polls::STATUS_INACTIVE ? Polls::STATUS_ACTIVE : Polls::STATUS_INACTIVE;
        $model->save();

        \Yii::$app->response->redirect(OrganizationUrl::to(["/polls/index"]));
    }

    public function actionDelete()
    {
        $model = Polls::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        if (!$model) {
            throw new \Exception("No such poll");
        }

        if (!$model->canEdit) {
            throw new \Exception("You don't have rights to do this");
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash("ok",\Yii::t("main","Голосование удалено"));
        } else {
            \Yii::$app->session->setFlash("error",\Yii::t("main","Ошибка удаления"));
        }

        \Yii::$app->response->redirect(OrganizationUrl::to(["/polls/index"]));
    }

    public function actionVote()
    {
        $model = Polls::find()->byOrganization()->byPk(\Yii::$app->request->get('id'))->one();
        if (!$model) {
            throw new \Exception("No such poll");
        }

        if ($model->myResult) {
            throw new \Exception("allready voted");
        }

        $vote = new PollResults();
        $vote->poll_id = $model->id;
        $vote->user_id = \Yii::$app->user->id;
        $vote->result = \Yii::$app->request->get("a");

        if ($vote->save()) {
            \Yii::$app->session->setFlash("ok",\Yii::t("main","Вы успешно проголосовали"));
        } else {
            var_dump($vote->getErrors());
            \Yii::$app->session->setFlash("error",\Yii::t("main","Ошибка голосования"));
        }

        \Yii::$app->response->redirect(OrganizationUrl::to(\Yii::$app->request->get("return") ?: ["/polls/index"]));
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['vote'],
                        'roles' => ['@']
                    ],
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

}