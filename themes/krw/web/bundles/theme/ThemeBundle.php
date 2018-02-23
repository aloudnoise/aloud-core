<?php

namespace krw\web\bundles\theme;

use yii\web\AssetBundle;

class ThemeBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@krw/web/bundles/theme/assets';
    public $css = [
        'css/theme.css',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}