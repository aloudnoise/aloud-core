<?php

namespace common\models\forms;

class FilterForm extends BaseFilterForm
{
    public $pr = 1;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['pr'], 'safe']
        ]);
    }

    public function applyFilter(&$query)
    {
        if ($this->pr == 1) {
            $query->byUser();
        }
        parent::applyFilter($query);
    }
}