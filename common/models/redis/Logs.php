<?php

namespace common\models\redis;

use common\traits\AttributesToInfoTrait;
use common\traits\LogsTrait;
use Yii;
use yii\redis\ActiveRecord;

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

    public function attributes()
    {
        return (new \common\models\Logs())->attributes();
    }
}
