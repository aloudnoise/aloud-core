<?php
namespace app\models\forms;

use app\traits\BackboneRequestTrait;
use common\models\forms\FilterForm;

class QuestionsFilterForm extends FilterForm
{

    use BackboneRequestTrait;

    public $theme_id = "";

    public function rules()
    {
        return array_merge([
            ['theme_id' , 'integer']
        ], parent::rules());
    }

    public function applyFilter(&$query)
    {

        if ($this->search) {
            $query->andWhere(["LIKE", "LOWER(name)", mb_strtolower($this->search, "UTF-8")]);
        }

        if ($this->theme_id) {

            $query->andWhere([
                "theme_id" => $this->theme_id
            ]);

        }
    }

}

?>