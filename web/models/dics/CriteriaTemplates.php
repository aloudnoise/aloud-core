<?php
namespace app\models\dics;

use app\models\DicValues;
use common\traits\AttributesToInfoTrait;

class CriteriaTemplates extends DicValues
{

    use AttributesToInfoTrait;

    public function attributesToInfo()
    {
        return ['template'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['template'], 'required'],
            [['template'], 'string', 'max'=>1000]
        ]);
    }

    public function formFields()
    {
        return [
            'template' => [
                'label' => \Yii::t("main","Шаблон оценивания"),
                'type' => 'text',
                'help' => \Yii::t("main","Пример: 0-40:2,41-60:3,61-80:4,81-100:5"),
            ]
        ];
    }

}