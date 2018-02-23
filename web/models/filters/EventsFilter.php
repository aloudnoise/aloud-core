<?php
namespace app\models\filters;

use common\models\forms\BaseFilterForm;

class EventsFilter extends BaseFilterForm
{

    public $month = null;
    public $year = null;
    public $day = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['month','year','day'], 'integer']
        ]);
    }

}