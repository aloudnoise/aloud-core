<?php

namespace common\queries;

use common\components\ActiveQuery;

class UsersQuery extends ActiveQuery
{

    public function registered($is_registered = 1)
    {
        return $this->andWhere([
            "is_registered" => $is_registered
        ]);
    }

}

?>