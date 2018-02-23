<?php
namespace bilimal\web\models\old;

use bilimal\common\components\ActiveRecord;

class Users extends ActiveRecord
{

    public static function tableName()
    {
        return 'system.users';
    }

    public static function getDb()
    {
        return \Yii::$app->db_bilimal_old;
    }

    public function getPerson()
    {
        return $this->hasOne(UsersInfo::className(), ['user_id' => 'id']);
    }

}