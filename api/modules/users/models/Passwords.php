<?php

namespace api\modules\users\models;

use common\components\Model;

class Passwords extends Model
{

    public $old_password = null;
    public $password = null;
    public $re_password = null;
    public $hash = null;

    public function rules()
    {
        return [
            [['old_password','password','re_password'], 'required'],
            [['password','re_password'],'string', 'min' => 6, 'max' => 16],
            [['re_password'], 'compare', 'compareAttribute' => 'password'],
            [['hash'], 'string']
        ];
    }

    public function save()
    {

        if ($this->validate()) {
            $user = \Yii::$app->user->identity;

            if ($this->hash) {

            }

            if ($user->password != md5($this->old_password))
            {
                $this->addError("old_password", "WRONG_PASSWORD");
                return false;
            }

            $user->password = md5($this->password);
            if (!$user->save(false)) {
                $this->addErrors($user->getErrors());
            }

            return true;

        }
        return false;

    }

}