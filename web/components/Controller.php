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
    public $is_mobile = false;
    public $mobile_device = '';

    /**
     * Определяем базовые константы АССЕТОВ
     * @param yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {

        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        {

            //Detect special conditions devices
            $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
            $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

            if( $iPod || $iPhone ){
                $this->mobile_device = 'iphone';
            }else if($iPad){
                $this->mobile_device = 'iphone';
            }else if($Android){
                $this->mobile_device = 'android';
            }else if($webOS){
                $this->mobile_device = 'webios';
            }

            $this->is_mobile = true;
        }

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
        return $this->actionsData($action);
    }

    public function prepareActionIndex()
    {
        $actionData = $this->getActionData('index');
        $filter = \Yii::createObject($actionData['filter']);
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (\Yii::$container->get($actionData['modelClass']))::find();
        $filter->applyFilter($query);

        $provider = Yii::createObject($actionData['provider'], [
            'query' => $query,
            'pagination' => $actionData['pagination']
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
        $model = Yii::createObject($actionData['add']['modelClass']);
        $model = $actionData['add']['find']($model, \Yii::$app->request->get());

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
                return $this->renderJSON($actionData['add']['success']($model, \Yii::$app->request->get()));
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