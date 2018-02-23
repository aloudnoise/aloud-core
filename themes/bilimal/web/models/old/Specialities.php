<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 30.01.2018
 * Time: 15:55
 */

namespace bilimal\web\models\old;


use bilimal\common\components\ActiveQuery;
use bilimal\common\components\ActiveRecord;
use bilimal\common\traits\ServerDbConnectionTrait;

class Specialities extends ActiveRecord
{

    use ServerDbConnectionTrait;

    public static function find()
    {
        return parent::find()->addSelect([
            "*",
            "name" => "specialities.\"name\"[1]"
        ]);
    }

    public static function tableName()
    {
        return 'specialities';
    }

}