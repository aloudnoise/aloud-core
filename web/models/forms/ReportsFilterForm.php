<?php

namespace app\models\forms;

use common\models\forms\FilterForm;

class ReportsFilterForm extends FilterForm
{

    public $since = "";

    public function rules()
    {
        return array_merge([
            ['since' , 'integer']
        ], parent::rules());
    }

    public function applyFilter(&$query)
    {
        if ($this->since) {

            $query['since'] = $this->since;

        }
    }

}

?>