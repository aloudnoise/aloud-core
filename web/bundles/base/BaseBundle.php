<?php

namespace app\bundles\base;

use yii\web\AssetBundle;

class BaseBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@app/bundles/base/assets';
    public $css = [
        'css/base.css',
        'css/loading.css'
    ];
    public $js = [
        'js/common.js',
        'js/base.js',
        'js/adapter.js',
        'js/jquery.slimscroll.min.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'jquery',
        'bootstrap',
        'urlmanager',
        'backbone'
    ];
}