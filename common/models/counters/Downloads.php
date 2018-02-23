<?php

namespace common\models\counters;

class Downloads extends CountersTemplate
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counters.downloads';
    }

}
