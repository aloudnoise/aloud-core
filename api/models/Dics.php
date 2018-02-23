<?php
namespace api\models;

class Dics extends \common\models\Dics
{
    public function fields()
    {
        $fields = [
            'ts'
        ];
        return $fields;
    }

    public function getValues()
    {
        return $this->hasMany(DicValues::className(), ['dic' => 'name']);
    }
}