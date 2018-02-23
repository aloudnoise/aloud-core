<?php
namespace common\models\materials;

use common\models\Materials;

class Files extends Materials
{
    public function attributesToInfo()
    {
        return ['file'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'required'],
            [['file'], 'filter', 'filter' => function($value) {
                return !is_array($value) ? json_decode($value, true) : $value;
            }]
        ]);
    }

}