<?php
namespace api\models;

class DicValues extends \app\models\DicValues
{
    public function fields()
    {
        return [
            'id',
            'name',
            'organization_type',
            'organization_id',
            'is_custom' => function($model) {
                return (int)$model->organization_id > 0;
            }
        ];
    }
}