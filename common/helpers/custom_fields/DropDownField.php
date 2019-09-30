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
    public $index = null;
    public $callback = null;


}