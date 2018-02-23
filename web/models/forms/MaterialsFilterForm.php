<?php

namespace app\models\forms;

use app\models\DicValues;
use common\models\forms\FilterForm;

class MaterialsFilterForm extends FilterForm
{

    public $type = null;
    public $tagsString = null;
    public $theme = null;

    public function rules()
    {
        return array_merge([
            [['type', 'theme'], 'safe']
        ], parent::rules());
    }

    public function applyFilter(&$query)
    {
        if ($this->search) {
            $query->andWhere(["LIKE", "LOWER(materials.name)", mb_strtolower($this->search, "UTF-8")]);
        }
        if ($this->type) {
            $query->andWhere(["type" => $this->type]);
        }
        if ($this->theme) {

            $ids = DicValues::find()->byOrganization()->andWhere([
                'dic' => 'DicQuestionThemes'
            ])->andWhere(["LIKE", "LOWER(name)" , mb_strtolower($this->theme, "UTF-8")])->select(['id'])->asArray()->column();

            $query->andWhere([
                'in', 'materials.theme_id', $ids
            ]);
        }

        if ($this->pr == 2) {
            $query->andWhere([
                'is_shared' => 1
            ]);
        }

        parent::applyFilter($query);

    }
}

?>