<?php

namespace app\controllers;

use app\components\View;
use app\helpers\OrganizationUrl;
use app\models\Dics;
use app\models\DicValues;
use app\traits\BackboneRequestTrait;
use app\components\Controller;
use common\models\forms\FilterForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

class DicsController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), Url::to(["/main/index"]));
        return $p;
    }

    public function actionList()
    {

        $filter = new FilterForm();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $dics = Dics::find()
            ->with(['values' => function($q) {
                return $q->byOrganizationOrNull()
                    ->notDeleted()
                    ->byOrganizationTypeOrNull();
            }])
            ->byOrganizationOrNull()
            ->byOrganizationTypeOrNull()
            ->orderBy("ts DESC");

        if ($filter->search) {
            $dics->andWhere(["LIKE", "description", $filter->search]);
        }

        $dics = $dics->all();
        return $this->render('@app/views/dics/list', [
            'filter' => $filter,
            'dics' => $dics
        ]);
    }

    public function actionAdd()
    {
        $dic = Dics::find()->byPk(\Yii::$app->request->get('dic'))->one();
        if (!$dic) throw new \Exception("NO DIC");

        $class = Dics::$classes[$dic->name] ? Dics::$classes[$dic->name] : new DicValues;
        $dicv = new $class();
        $dicv->dic = $dic->name;
        if (\Yii::$app->request->get('id')) {
            $dicv = $class::find()->byPk(\Yii::$app->request->get('id'))->one();
            if (!$dicv->isInOrganization)
            {
                throw new \Exception("NO WAY");
            }
        }

        if (\Yii::$app->request->post('DicValues')) {
            $dicv->attributes = \Yii::$app->request->post('DicValues');
            if ($dicv->save())
            {
                \Yii::$app->session->setFlash('ok',\Yii::t('main', 'Значение успешно добавлено'));
                if (!\Yii::$app->request->get("from")) {
                    return $this->renderJSON([
                        'redirect' => \Yii::$app->request->get("return") ?: null
                    ]);
                }
            } else {
                return $this->renderJSON($dicv->getErrors(), true);
            }

        }

        \Yii::$app->data->dic = BackboneRequestTrait::arrayAttributes($dic, [],  ['name'], true);
        \Yii::$app->data->dicv = BackboneRequestTrait::arrayAttributes($dicv, [],  array_merge(['id', 'name'] , array_keys($dicv->formFields())), true);
        \Yii::$app->data->rules = $dicv->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $dicv->attributeLabels();

        return $this->render("@app/views/dics/form", [
            'dicv' => $dicv,
            'dic' => $dic
        ]);
    }

    public function actionDelete()
    {

        if (\Yii::$app->request->get('id')) {
            $dicv = DicValues::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($dicv AND $dicv->isInOrganization AND $dicv->delete()) {

            \Yii::$app->session->setFlash("success",\Yii::t("main","Значение успешно удалено"));

        }

        \Yii::$app->response->redirect(\Yii::$app->request->get("return") ?: OrganizationUrl::to(['/dics/list']), [
            'scroll' => false
        ]);

    }

    public function actionAutocomplete()
    {

        $data = DicValues::find()->filterWhere(["like", "dic_values.name", \Yii::$app->request->get('query')])
            ->byOrganizationOrNull()
            ->andWhere([
                "dic" => \Yii::$app->request->get('dic')
            ])
            ->distinct(true)->all();

        $result = [
            "query"=>\Yii::$app->request->get('query'),
            "suggestions"=>[]
        ];
        if (!empty($data)) {
            foreach($data as $d) {
                $result['suggestions'][] = [
                    "value" => $d->name,
                    "data" => $d->id
                ];
            }
        }
        return $this->renderJSON($result);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['autocomplete']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['teacher'],
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