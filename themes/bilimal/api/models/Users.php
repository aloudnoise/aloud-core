<?php
namespace bilimal\api\models;

use bilimal\web\models\BilimalUser;

class Users extends \api\models\Users
{

    public function getBilimalUser()
    {
        return $this->hasOne(BilimalUser::className(), ['user_id' => 'id']);
    }

    public function getIsBilimal()
    {
        return $this->bilimalUser ? true : false;
    }

}