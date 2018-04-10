<?php

namespace aloud_core\api\components;

use aloud_core\common\components\ActiveRecord;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecordInterface;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Базовый контроллер всех контроллеров
 * Class ActiveController
 * @package aloud_core\api\components
 */
class ActiveController extends \yii\rest\ActiveController
{

    public $reservedParams = ['sort','q','page','per-page'];
    public $per_page = 100;

    public $deep_cache = false;

    public $serializer = 'aloud_core\api\components\Serializer';
    public $scenario = 'default';

    public function actionInfo()
    {
        return (new $this->modelClass())->info();
    }

    /**
     * Стандартные действия контроллера
     * @return array
     */
    public function actions() {

        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        $actions['index']['prepareDataProvider'] = [$this, 'fetchRecords'];
        $actions['view']['findModel'] = [$this, 'findModel'];

        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
        ];

        return $actions;
    }

    /**
     * Returns the data model based on the primary key given.
     * If the data model is not found, a 404 HTTP exception will be raised.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return ActiveRecordInterface the model found
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $query = $modelClass::find()->byPk(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $query = $modelClass::find()->byPk($id);
        }

        /* @var $query ActiveQuery */

        $search = [];
        if (!empty($params)) {
            unset($params['id']);
            foreach ($params as $key => $value) {
                if (!in_array(strtolower($key), $this->reservedParams)) {
                    $search[$key] = $value;
                }
            }
        }

        $filter = new $modelClass;
        $filter->scenario = ActiveRecord::FILTER_ONE_SCENARIO;
        $filter->attributes =  $search;
        if (!$filter->validate()) {
            return $filter;
        }
        // Применяем фильтр к запросу
        $filter->applyFilterOne($query);

        \Yii::$app->cache->pause();

        $result = $query->one();

        \Yii::$app->cache->resume();

        if (isset($result)) {
            return $result;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }

    /**
     * Подготовка запроса, перед отдачей клиенту
     * @return ActiveDataProvider
     */
    public function prepareQuery() {

        $params = \Yii::$app->request->queryParams;
        if ($this->deep_cache) {
            $cache_string = "deep_".$this->action->id.md5(json_encode($params).\Yii::$app->user->id);
            $cached = \Yii::$app->cache->get($cache_string);
            if ($cached) {
                \Yii::trace("SERVED FROM DEEP CACHE");
                return $cached;
            }
        }

        $modelClass = $this->modelClass;
        $search = [];
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (!in_array(strtolower($key), $this->reservedParams)) {
                    $search[$key] = $value;
                }
            }
        }
        $query = $modelClass::find();

        $filter = new $modelClass;
        $filter->scenario = ActiveRecord::FILTER_SCENARIO;
        $filter->attributes =  $search;
        if (!$filter->validate()) {
            return $filter;
        }
        // Применяем фильтр к запросу
        $filter->applyFilter($query);

        return $query;
    }

    /**
     * Отдача провайдера клиенту
     * @return ActiveDataProvider
     * @internal param $query
     */
    public function fetchRecords()
    {
        $query = $this->prepareQuery();
        if ($query instanceof ActiveQuery) {
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'defaultPageSize' => $this->per_page,
                    'pageSizeLimit' => [1, 1000]
                ]
            ]);
        } else {
            return $query;
        }
    }

    public function actionCreate()
    {
        \Yii::$app->cache->pause();
        $model = new $this->modelClass();
        $this->checkAccess($this->id, $model);

        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess($this->id, $model);
        $model->scenario = ActiveRecord::SCENARIO_UPDATE;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    public function actionDelete()
    {

        $id = \Yii::$app->request->get("id");
        if ($id) {
            $model = $this->findModel($id);
            $this->checkAccess($this->id, $model);

            if ($model->delete() === false) {
                throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
            }
            Yii::$app->getResponse()->setStatusCode(204);
        } else {
            \Yii::$app->getResponse()->setStatusCode(404);
            throw new ServerErrorHttpException('Model NOT FOUND');

        }
    }

    public function actionCount()
    {
        $query = $this->prepareQuery();
        /**
         * @var $query ActiveQuery
         */
        return $query->count();
    }

    public function behaviors()
    {

        $result = ArrayHelper::merge(parent::behaviors(), [
            'actionTime' => [
                'class' => 'aloud_core\api\components\ActionTimeFilter',
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['options'],
            ],
        ]);

        // remove authentication filter
        $auth = $result['authenticator'];
        unset($result['authenticator']);

        /** @var $serializer Serializer */
        $serializer = $this->serializer;

        // add CORS filter
        $result['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
            ],

        ];
        // re-add authentication filter
        $result['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $result['authenticator']['except'] = ['options'];

        $result['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true
                ],
            ],
        ];


        return $result;
    }

    public function afterAction($action, $result)
    {
        $r = parent::afterAction($action, $result);
        $serializer = new Serializer();
        // TODO: Сделан костыль изза ошибки в самом фреймворке
        \Yii::$app->response->headers->set("Access-Control-Expose-Headers", implode(",",[strtolower($serializer->currentPageHeader), strtolower($serializer->totalCountHeader), strtolower($serializer->totalCountHeader), strtolower($serializer->pageCountHeader), strtolower($serializer->perPageHeader)]));
        return $r;
    }

}

?>