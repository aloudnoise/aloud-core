<?php

namespace common\models;

use common\components\ActiveRecord;
use common\traits\LogsTrait;
use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property int $user_id
 * @property int $organization_id
 * @property string $route
 * @property string $info
 * @property string $ip
 * @property string $ts
 */
class Logs extends ActiveRecord
{

    use LogsTrait;

    const TYPE_ROUTE = 'route';
    const TYPE_MODEL = 'model';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

}
