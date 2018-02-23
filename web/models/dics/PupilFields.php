<?php
namespace app\models\dics;

use app\models\DicValues;
use common\traits\AttributesToInfoTrait;

class PupilFields extends DicValues
{

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['value'], 'required'],
            [['value'], 'string', 'max'=>1000]
        ]);
    }

    public function formFields()
    {
        return [
            'value' => [
                'label' => \Yii::t("main","Латинское название поля (уникальное)"),
                'type' => 'text'
            ]
        ];
    }

}