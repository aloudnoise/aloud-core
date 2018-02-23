<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 23.01.2018
 * Time: 0:43
 */

namespace common\models\materials;


use common\models\Materials;

class Links extends Materials
{

    public function attributesToInfo()
    {
        return ['external_link'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['external_link'], 'required']
        ]);
    }

}