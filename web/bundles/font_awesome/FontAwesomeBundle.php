<?php

namespace aloud_core\web\bundles\font_awesome;

use yii\web\AssetBundle;

class FontAwesomeBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/font_awesome/assets';
    public $css = [
        'css/all.min.css'
    ];
    
    public $publishOptions = [
        'forceCopy' => false
    ];
}

?>
