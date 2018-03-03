<?php
namespace aloud_core\web\components;

use aloud_core\web\bundles\backbone\BackboneBundle;
use aloud_core\web\bundles\base\BaseBundle;
use yii\helpers\Url;

class View extends \yii\web\View
{

    public function endBody()
    {

        echo $this->render("@aloud_core/web/views/common/controller_modal_template");
        echo $this->render("@aloud_core/web/views/common/controller_template");
        echo $this->render("@aloud_core/web/views/common/loading");

        $this->registerJs("// Some php vars
                window.CORE = {};
                CORE.BACKBONE_ASSETS = '".BackboneBundle::register($this)->baseUrl."';
                CORE.BACKBONE_CLIENT_ASSETS = '".(\Yii::$app->aloud_core['backbone_client_bundle'])::register($this)->baseUrl."';
                CORE.BASE_ASSETS = '".BaseBundle::register($this)->baseUrl."';
                CORE.DEBUG = ".intval(YII_DEBUG).";
                CORE.URL_ROOT = '';
                CORE.SOCKET_URL = '".\Yii::$app->aloud_core['socket_server']."';
                CORE.API_URL = '".\Yii::$app->aloud_core['api_url']."';
                CORE.FILES_HOST = '".\Yii::$app->aloud_core['files_host']."';
                CORE.TRACKING_CODE = false;
            ", View::POS_HEAD, 'constants');

        $p = \Yii::$app->request->get();
        if (isset($p['z'])) {
            $murl = $p['z'];
        }
        unset($p['z']);
        $baseUrl = Url::to(array_merge(['/'.\Yii::$app->controller->route], $p));

        $model = \Yii::$app->response->getModelData();

        $this->registerJs('
                    
                Yii.app = _.extend(new '.\Yii::$app->aloud_core['js_application_class'].'({}), Yii.app);

                // Init current controller
                Yii.app.user = '.json_encode(\Yii::$app->user->identity ? \Yii::$app->user->identity->backboneArray() : ['isGuest' => true]).';

                Yii.app.renderController({
                        model: '.json_encode($model).',
                    },
                    "normal",
                    {
                        loaded : true,
                        href : \''.$baseUrl.'\',
                        baseUrl : \''.$baseUrl.'\',
                        noState : '.($model['isModal'] ? "true" : "false").',
                        transaction : true
                    }
                );

                Yii.app.render();
        
        ', self::POS_READY, 'application_init');

        parent::endBody();
    }

    public function setTitle($title, $breadcrumbs = true)
    {
        $this->title = $title;
        \Yii::$app->data->pageTitle = $title;
        if ($breadcrumbs) \Yii::$app->breadCrumbs->addLink($title);
    }

    public function addTitle($title, $breadcrumbs = true)
    {
        $this->title = $this->title." ".$title;
        \Yii::$app->data->pageTitle = $this->title;
        if ($breadcrumbs) \Yii::$app->breadCrumbs->addLink($title);
    }

    public function registerAssetBundle($name, $position = null)
    {
        if (!\Yii::$app->request->isAjax) {
            //run the parent method to parse the less/css file like Yii normally does.
            return parent::registerAssetBundle($name, $position);
        } else {

            /* @var $bundle \yii\web\AssetBundle */
            $bundle = parent::registerAssetBundle($name, $position);

            if (isset($bundle->static) AND $bundle->static == true) {
                return $bundle;
            }

            $url = $bundle->baseUrl."/";
            if ($bundle->css) {
                foreach ($bundle->css as $css) {
                    \Yii::$app->data->append("css",$url.$css);
                }
            }

            if ($bundle->js) {
                foreach ($bundle->js as $js) {
                    \Yii::$app->data->append("js",$url.$js);
                }
            }

            return $bundle;

        }
    }

    public function registerCssFile($url, $options = [], $key = null)
    {

        if (!\Yii::$app->request->isAjax) {
            //run the parent method to parse the less/css file like Yii normally does.
            return parent::registerCssFile($url, $options, $key);
        } else {

            \Yii::$app->data->append("css",$url);

        }
    }

    public function registerJsFile($url, $options = [], $key = null)
    {
        if (!\Yii::$app->request->isAjax) {
            return parent::registerJsFile($url, $options, $key);
        } else {
            \Yii::$app->data->append("js",$url);
        }
    }

    public function registerJs($js, $position = self::POS_READY, $key = null, $options = [])
    {

        if (!\Yii::$app->request->isAjax) {
            //run the parent method to parse the less/css file like Yii normally does.
            return parent::registerJs($js, $position, $key);
        } else {
            if (!$key) $key = uniqid();
            $s = array("id" => $key, "script" => $js);
            if (isset($options['force'])) {
                $s['force'] = true;
            }
            \Yii::$app->data->append("js", $s);

        }
    }

}
?>