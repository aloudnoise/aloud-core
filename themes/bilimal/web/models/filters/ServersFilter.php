<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 19.02.2018
 * Time: 14:56
 */

namespace bilimal\web\models\filters;


use common\models\forms\BaseFilterForm;

class ServersFilter extends BaseFilterForm
{

    public function applyFilter(&$query)
    {

        if ($this->search) {
            $query->andWhere(['like', 'LOWER(caption[1])', mb_strtolower($this->search, "UTF-8")]);
        }

        parent::applyFilter($query);
    }

}