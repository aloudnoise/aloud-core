<?php
namespace aloud_core\common\helpers\custom_fields;

use yii\base\BaseObject;

class BaseField extends BaseObject
{

    public $label = null;

    public static function instantiate($config) {

        $types = [
            'text' => BaseField::className(),
            'text_area' => TextAreaField::className(),
            'select' => DropDownField::className(),
            'json' => JsonField::className()
        ];

        $type = $config['type'];
        unset($config['type']);

        return new $types[$type]($config);

    }

}