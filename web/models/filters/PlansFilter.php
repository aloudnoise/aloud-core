<?php
namespace app\models\filters;

use common\models\forms\BaseFilterForm;

class PlansFilter extends BaseFilterForm
{

    public $year = null;
    public $month = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['year', 'month'], 'integer']
        ]);
    }

}