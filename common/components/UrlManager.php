<?php

namespace aloud_core\common\components;

class UrlManager extends \yii\web\UrlManager
{
    public $langParam = 'ln';
    public $languages = ['ru-RU', 'kk-KZ'];
}