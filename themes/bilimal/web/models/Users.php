<?php
namespace bilimal\web\models;


class Users extends \app\models\Users
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