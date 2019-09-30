<?php
namespace aloud_core\common\helpers\custom_fields;

use aloud_core\common\helpers\Common;
use aloud_core\web\helpers\ArrayHelper;
use common\models\DicValues;
use yii\base\BaseObject;

class BaseField extends BaseObject
{

    public $label = null;
    public $help = null;
    public $languages = false;
    public $options = [];
    public $parse = null;
    public $data = null;

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

    public function fetchData()
    {
        if (is_string($this->data)) {
            return ArrayHelper::map(DicValues::findByDic($this->data), 'id', 'nameByLang');
        }

        if (is_array($this->data)) {
            return array_map(function($a) {
                return Common::byLang($a);
            }, $this->data);
        }

        if (is_callable($this->data)) {
            $func = $this->data;
            return $func($this);
        }

        return [];

    }

}