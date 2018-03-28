<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 28.03.2018
 * Time: 17:20
 */

namespace common\components;


class ColumnSchema extends \yii\db\pgsql\ColumnSchema
{

    public $disableJsonSupport = true;

}