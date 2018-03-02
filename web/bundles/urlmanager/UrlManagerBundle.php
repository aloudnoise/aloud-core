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

        if (YII_DEBUG || $_GET['force_copy'] == 1) {
            $this->compileJS();
        }
        parent::registerAssetFiles($view);

    }


    public function compileJS()
    {
        $urlManager = \Yii::$app->urlManager;
        $managerVars = get_object_vars($urlManager);
        $managerVars['urlFormat'] = $urlManager->enablePrettyUrl ? "path" : false;

        $managerVars['rules'] = [];

        if (\Yii::$app->urlManager->rules) {
            foreach (\Yii::$app->urlManager->rules as $pattern => $route) {
                //Ignore custom URL classes
                if (isset($route->name) AND isset($route->route)) {
                    $managerVars['rules'][$route->name] = $route->route;
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

}

?>
