<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 26.04.2018
 * Time: 0:08
 */

namespace aloud_core\web\bundles\vue;


use yii\web\AssetBundle;

class VueBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/vue/assets';
    public $js = [
        'vue.min.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
    ];

    public $publishOptions = [
        'forceCopy' => false
    ]
    
}