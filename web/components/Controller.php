<?php
namespace aloud_core\web\components;

use aloud_core\web\components\InstitutionFilter;
use aloud_core\web\traits\BackboneRequestTrait;
use app\helpers\OrganizationUrl;
use app\helpers\Url;
use common\models\forms\BaseFilterForm;
use common\models\Organizations;
use common\models\redis\OnlineUsers;
use yii;
use yii\filters\AccessControl;

class Controller extends yii\base\Controller
{

    public $isModal = false;
    public $external = false;
    public $body_custom_classes = 'h-100 ';

    /**
     * Определяем базовые константы АССЕТОВ
     * @param yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {

        if (!\Yii::$app->request->isAjax) {
            \aloud_core\web\bundles\tools\ToolsBundle::registerJgrowl($this->view);
            \aloud_core\web\bundles\tools\ToolsBundle::registerTool($this->view, 'waves');
        }

        if ($this->module->id !== 'app') {
            $modules = [];
            $module = $this->module;
            while ($module->module) {
                array_unshift($modules, $module->id);
                $module = $module->module;
            }
            \Yii::$app->data->modules = $modules;
        }

        \Yii::$app->data->GET = \Yii::$app->request->get();
        \Yii::$app->data->controller = $this->id;
        \Yii::$app->data->action = $action->id;
        \Yii::$app->data->size = 'lg';

        if (in_array(\Yii::$app->request->post('target'), ['modal'])) {
            \Yii::$app->data->isModal = true;
            $this->isModal = true;
        } else {
            $get = \Yii::$app->request->get();
            unset($get['z']);
            \Yii::$app->data->baseUrl = yii\helpers\Url::to(array_merge(['/' . $this->route], $get));
        }

        \Yii::$app->data->language = \Yii::$app->language;

        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        if ($this->external) {
            $this->external = false;
            $external = true;
        }
        \Yii::$app->data->body_custom_classes = $this->body_custom_classes." ".$this->layout;
        if ($external) {
            $this->external = true;
        }

        return parent::afterAction($action, $result);
    }


    /**
     * Возвращает JSON результат для аякс запросов
     * @param $data
     * @param mixed $code
     */
    public function renderJSON($data, $code = false)
    {
        if ($code === true) {
            \Yii::$app->response->statusCode = 500;
        } else if ($code === false) {
            \Yii::$app->response->statusCode = 200;
        } else {
            \Yii::$app->response->statusCode = $code;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    public function render($view, $params = [])
    {
        if ($this->external) {
            return parent::renderPartial("@aloud_core/web/views/common/controller_layout", [
                    "content" => $this->renderPartial($view, $params)
                ]);
        }

        $content = $this->getView()->render($view, $params, $this);
        $content = parent::renderPartial("@aloud_core/web/views/common/controller_layout", [
            "content" => $content
        ]);
        return $this->renderContent($content);

    }
    
    public static $modelClass = null;
    public static function getModel($id = null, $throwOnEmpty = true)
    {
        if (static::$modelClass) {
            $id = $id ?: \Yii::$app->request->get("id");
            $modelClass = static::$modelClass;
            $model = new $modelClass();
            if ($id) {
                $model = $modelClass::find()->byPk($id)->notDeleted()->one();
                if (!$model AND $throwOnEmpty) {
                    throw new \Exception("NO");
                }
            }
            return $model;
        }
        throw new \RuntimeException("specify modelClass property");
    }

    public $actionParams = [];    
    public $filterClass = BaseFilterForm::class;
    public function actionsData()
    {
        return [
            'index' => [
                'modelClass' => static::$modelClass,
                'filterClass' => $this->filterClass,
                'provider' => [
                    'class' => yii\data\ActiveDataProvider::class,
                    'pagination' => new yii\data\Pagination([
                        'pageSize' => 50
                    ]),
                ],
                'template' => 'index'
            ],
            'add' => [
                'modelClass' => static::$modelClass,
                'template' => 'form',
                'find' => function($model, $get) {
                    if ($get['id']) {
                        return $model::find()->byPk($get['id'])->one();
                    }
                    return $model;
                },
                'serialize' => [
                    'relations' => [],
                    'fields' => []
                ],
                'success' => function($model, $get) {
                    return [];
                }
            ],
            'delete' => [
                'modelClass' => static::$modelClass,
                'find' => function($model, $get) {
                    if ($get['id']) {
                        return $model::find()->byPk($get['id'])->one();
                    }
                    return $model;
                },
                'redirect' => function($model, $get) {
                    return [
                        \Yii::$app->request->referrer
                    ];
                }
            ]
        ];
    }
    public function getActionData($action) {
        return $this->actionsData()[$action];
    }

    public function prepareActionIndex()
    {
        $actionData = $this->getActionData('index');
        $filter = \Yii::createObject($actionData['filterClass']);
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (\Yii::$container->get($actionData['modelClass']))::find();

        $filter->applyFilter($query);

        $provider = Yii::createObject([
            'class' => $actionData['provider']['class'],
            'query' => $query,
            'pagination' => $actionData['provider']['pagination']
        ]);;

        \Yii::$app->data->filter = BackboneRequestTrait::arrayAttributes($filter, [], $filter->attributes(), true);

        $this->actionParams = array_merge([
            "provider" => $provider,
            "filter" => $filter,
        ], $this->actionParams);
    }
    public function actionIndex()
    {
        $this->prepareActionIndex();
        return $this->renderAction('index');
    }

    public function renderAction($action) {
        $actionsData = $this->getActionData($action);
        return $this->render($actionsData['template'], $this->actionParams);
    }

    public function prepareActionAdd()
    {
        $actionData = $this->getActionData('add');
        $model = Yii::createObject($actionData['modelClass']);
        $model = $actionData['find']($model, \Yii::$app->request->get());

        if ($model->isNewRecord) {
            if (!$model->canAdd) {
                throw new yii\base\Exception('ACCESS DENIED');
            }
        } else {
            if (!$model->canEdit) {
                throw new yii\base\Exception('ACCESS DENIED');
            }
        }

        if (\Yii::$app->request->post($model::className())) {

            $model->attributes = \Yii::$app->request->post($model::className());
            if ($model->save()) {
                return $this->renderJSON($actionData['success']($model, \Yii::$app->request->get()));
            }
            return $this->renderJSON($model->getErrors(), true);

        }
        $this->actionParams = array_merge([
            "model" => $model,
        ], $this->actionParams);
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, $actionData['serialize']['relations'], $actionData['serialize']['fields'], true);
    }

    public function actionAdd()
    {
        $this->prepareActionAdd();
        return $this->renderAction('add');
    }

    public function actionDelete()
    {
        $actionData = $this->getActionData('delete');
        $model = Yii::createObject($actionData['add']['modelClass']);
        $model = $actionData['find']($model, \Yii::$app->request->get());
        if ($model AND $model->canDelete AND $model->delete()) {
            \Yii::$app->session->setFlash("ok", \Yii::t("main","Запись удалена"));
        }
        return \Yii::$app->response->redirect($actionData['redirect']($model, \Yii::$app->request->get));
    }

}