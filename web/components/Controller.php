<?php
namespace aloud_core\web\components;

use aloud_core\web\components\InstitutionFilter;
use common\models\Organizations;
use common\models\redis\OnlineUsers;
use yii;
use yii\filters\AccessControl;

class Controller extends yii\base\Controller
{

    public $layout = '@app/views/layouts/main';
    public $isModal = false;

    public $external = false;

    /**
     * Определяем базовые константы АССЕТОВ
     * @param yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {

        OnlineUsers::setOnline();

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

        \Yii::$app->data->organization = Organizations::getCurrentOrganization() ? Organizations::getCurrentOrganization()->toArray() : [];
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

    public static $modelClass = null;
    public static function getModel($id = null, $throwOnEmpty = true)
    {
        if (static::$modelClass) {
            $id = $id ?: \Yii::$app->request->get("id");
            $modelClass = static::$modelClass;
            $model = new $modelClass();
            if ($id) {
                $model = $modelClass::find()->byPk($id)->byBusiness()->notDeleted()->one();
                if (!$model AND $throwOnEmpty) {
                    throw new \Exception("NO");
                }
            }
            return $model;
        }
        throw new \RuntimeException("specify modelClass property");
    }


    public function behaviors()
    {

        $behaviors = [];
        if (\Yii::$app->user->isGuest AND \Yii::$app->request->get("access-token")) {
            $behaviors['authenticator'] = [
                'class' => yii\filters\auth\QueryParamAuth::className(),
            ];
        }

        return array_merge($behaviors, [
            'organization' => [
                'class' => OrganizationFilter::className()
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }

    public function render($view, $params = [])
    {
        if ($this->external) {
            return parent::renderPartial("@app/views/common/controller", [
                    "content" => $this->renderPartial($view, $params)
                ]);
        }
        return parent::render($view, $params);
    }

}