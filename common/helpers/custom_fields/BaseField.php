<?php
namespace aloud_core\common\helpers\custom_fields;

use yii\base\BaseObject;

class BaseField extends BaseObject
{

    public $label = null;
    public $help = null;
    public $languages = false;
    public $options = [];

    public static function instantiate($config) {

        $types = [
            'text' => BaseField::className(),
            'text_area' => TextAreaField::className(),
            'select' => DropDownField::className(),
            'json' => JsonField::className()
        ];

        $type = $config['type'];
        unset($config['type']);

        $config['class'] = $types[$type];
        return \Yii::createObject($config);

    }

}