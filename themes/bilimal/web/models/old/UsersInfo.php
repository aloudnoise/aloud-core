<?php
namespace bilimal\web\models\old;

use bilimal\common\components\ActiveRecord;

class UsersInfo extends ActiveRecord
{

    public static function getDb()
    {
        return \Yii::$app->db_bilimal_old;
    }

}