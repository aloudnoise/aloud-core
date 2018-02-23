<?php

namespace common\models\forms;

use common\components\Model;

class BaseFilterForm extends Model
{
    public $search = '';
    public $id = null;

    public $tags = null;

    public $ids = [];
    public $exclude_ids = [];
    public $id_column = 'id';

    public function rules()
    {
        return [
            [['search', 'id', 'tags'], 'safe'],
            [['ids','exclude_ids'], 'filter', 'filter' => function($value) {
                return !is_array($value) ? explode(",", $value) : $value;
            }],
            [['ids','exclude_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    public function applyFilter(&$query)
    {

        if ($this->ids) {
            $query->andWhere([
                'in', $query->alias.'.'.$this->id_column, $this->ids
            ]);
        }

        if ($this->exclude_ids) {
            $query->andWhere([
                'not in', $query->alias.'.'.$this->id_column, $this->exclude_ids
            ]);
        }

        if ($this->tags) {
            $query->joinWith('tags');
            $tags = [];
            foreach ($this->tags as $tag) {
                $tags[] = mb_strtolower($tag, "UTF-8");
            }
            $query->andWhere(["in", "LOWER(tags.name)" , $tags]);
        }
    }
}