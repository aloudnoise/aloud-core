<?php
namespace aloud_core\common\helpers\custom_fields;

use aloud_core\common\helpers\Common;
use aloud_core\web\helpers\ArrayHelper;
use common\models\DicValues;
use yii\db\Query;

class DropDownField extends BaseField
{

    /**
     * Может принимать как обычный массив с данными, строку с названием словаря, а также ActiveQuery
     * @var null
     */
    public $data = null;
    public $index = null;
    public $callback = null;

    public function fetchData()
    {
        if (is_string($this->data)) {
            return ArrayHelper::map(DicValues::fromDic($this->data), 'id', 'nameByLang');
        }

        if (is_array($this->data)) {
            return array_map(function($a) {
                return Common::byLang($a);
            }, $this->data);
        }

        return [];

    }

}