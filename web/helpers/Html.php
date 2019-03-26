<?php

namespace aloud_core\web\helpers;

use aloud_core\common\helpers\Common;
use aloud_core\common\helpers\custom_fields\BaseField;
use aloud_core\common\helpers\custom_fields\DropDownField;
use aloud_core\common\helpers\custom_fields\JsonField;
use aloud_core\common\helpers\custom_fields\TextAreaField;
use aloud_core\common\helpers\custom_fields\ToggleField;
use aloud_core\web\widgets\EJsonEditor\EJsonEditor;

class Html extends \yii\helpers\Html
{

    // TODO Дополнить
    public static function customField($name, $value, $field, $options = []) {

        $field = !is_object($field) ? BaseField::instantiate($field) : $field;
        $options = array_merge($options, $field->options);

        if ($field instanceof DropDownField) {
            return static::dropDownList($name, $value, $field->fetchData(), $options);
        }

        if ($field instanceof TextAreaField) {
            return static::textarea($name, $value, $options);
        }

        if ($field instanceof ToggleField) {
            return static::checkbox($name, $value, $options);
        }

        if ($field instanceof JsonField) {
            return EJsonEditor::widget([
                'attribute' => $name,
                'value' => $value
            ]);
        }

        if ($field instanceof BaseField) {
            return static::textInput($name, $value, $options);
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