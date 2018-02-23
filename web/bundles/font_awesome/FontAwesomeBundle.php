<?php

namespace app\bundles\font_awesome;

use yii\web\AssetBundle;

class FontAwesomeBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@app/bundles/font_awesome/assets';
    public $css = [
        'css/font-awesome.min.css'
    ];
}

?>
