<?php

namespace app\bundles\theme;

use yii\web\AssetBundle;

class ThemeBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@app/bundles/theme/assets';
    public $css = [
        'css/theme.css',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}