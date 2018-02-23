<?php

namespace common\models\counters;

class Views extends CountersTemplate
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counters.views';
    }

}
