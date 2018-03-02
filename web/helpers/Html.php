<?php

namespace aloud_core\web\helpers;

class Html extends \yii\helpers\Html
{

    public static function dropDownList($name, $selection = null, $items = [], $options = [])
    {
        if (isset($options['empty'])) {
            $items = [""=>$options['empty']] + $items;
        }
        return parent::dropDownList($name, $selection, $items, $options);
    }

    public static function encode($content, $doubleEncode = true)
    {
        if (substr($content, 0, 2) == "<%") {
            return $content;
        }
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, \Yii::$app ? \Yii::$app->charset : 'UTF-8', $doubleEncode);
    }

}

?>