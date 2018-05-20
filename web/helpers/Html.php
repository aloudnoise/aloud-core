<?php

namespace aloud_core\web\helpers;

use aloud_core\common\helpers\Common;

class Html extends \yii\helpers\Html
{

    // TODO Дополнить
    public static function customField($name, $value, $field, $options = []) {

        if ($field['type'] == "text") {
            return static::textInput($name, $value, $options);
        }

        if ($field['type'] == "select") {
            $data = [];
            foreach ($field['data'] as $k=>$d) {
                $data[$k] = Common::byLang($d);
            }
            return static::dropDownList($name, $value, $data, $options);
        }

        if ($field['type'] == 'text_area') {
            return static::textarea($name, $value, $options);
        }

    }

    public static function dropDownList($name, $selection = null, $items = [], $options = [])
    {
        if (isset($options['empty'])) {
            $items = [""=>$options['empty']] + $items;
        }
        return parent::dropDownList($name, $selection, $items, $options);
    }

    public static function encode($content, $doubleEncode = true)
    {

        if (is_string($content)) {
            if (substr($content, 0, 2) == "<%") {
                return $content;
            }
        }
        return parent::encode($content, $doubleEncode);
    }

}

?>