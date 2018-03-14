<?php

namespace aloud_core\web\bundles\urlmanager;

use app\components\VarDumper;
use yii\web\View;
use yii\helpers\Json;
use yii\web\AssetBundle;

class UrlManagerBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/urlmanager/assets';
    public $css = [];
    public $js = [
        'PHPJS.dependencies.js',
        'Yii.UrlManager.js',
        'Yii.UrlManager.definitions.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public function registerAssetFiles($view) {

        parent::registerAssetFiles($view);
        $this->compileJS($view);

    }


    public function compileJS($view)
    {

        $file = \Yii::getAlias($this->basePath)."/"."Yii.UrlManager.definitions.js";
        if (!file_exists($file) OR (YII_DEBUG || $_GET['force_copy'] == 1)) {

            $urlManager = \Yii::$app->urlManager;
            $managerVars = get_object_vars($urlManager);
            $managerVars['urlFormat'] = $urlManager->enablePrettyUrl ? "path" : false;

            $managerVars['rules'] = \Yii::$app->config['components']['urlManager']['rules'];

            if (\Yii::$app->config['components']['urlManager']['rules']) {
                foreach (\Yii::$app->config['components']['urlManager']['rules'] as $pattern => $route) {
                    //Ignore custom URL classes
                    if(is_array($route) && isset($route['class'])) {
                        unset($managerVars['rules'][$pattern]);
                    }
                }
            }

            $encodedVars = Json::encode($managerVars);

            $baseUrl = \Yii::$app->getRequest()->getBaseUrl();
            $scriptUrl = \Yii::$app->getRequest()->getScriptUrl();
            $hostInfo = \Yii::$app->getRequest()->getHostInfo();

            $dir = \Yii::getAlias($this->basePath);
            file_put_contents($dir."/".$this->js[count($this->js)-1], "$(function() {
                Yii = Yii || {}; Yii.app = $.extend(Yii.app, {scriptUrl: '{$scriptUrl}',baseUrl: '{$baseUrl}',
                hostInfo: '{$hostInfo}'});
                Yii.app.urlManager = new UrlManager({$encodedVars});
                Yii.app.createUrl = function(route, params, ampersand)  {
                return this.urlManager.createUrl(route, params, ampersand);};
            });");

        }
        /* @var $view \aloud_core\web\components\View */
        $view->registerJsFile($this->baseUrl."/"."Yii.UrlManager.definitions.js",$this->jsOptions);


    }

}

?>
