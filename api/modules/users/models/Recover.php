<?php

namespace api\modules\users\models;

use common\components\Model;

class Recover extends Model
{

    public $email = null;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function save()
    {

        if ($this->validate()) {



        }
        return false;

    }

}