<?php

namespace aloud_core\web\bundles\jsoneditor;

use yii\web\AssetBundle;

class JsonEditorBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/jsoneditor/assets';
    public $js = [
        'jsoneditor.min.js',
    ];
    public $css = [
        'jsoneditor.min.css',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $publishOptions = [
        'forceCopy' => false
    ];
}