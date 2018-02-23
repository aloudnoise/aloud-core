<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 22.01.2018
 * Time: 13:53
 */

namespace bilimal\web\models\old;


use bilimal\common\components\ActiveRecord;
use bilimal\common\traits\ServerDbConnectionTrait;

class SchoolUsers extends ActiveRecord
{

    use ServerDbConnectionTrait;

    public static function tableName()
    {
        return 'system.users';
    }

}