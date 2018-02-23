<?php
namespace bilimal\web\models\old;

use bilimal\common\traits\ServerDbConnectionTrait;
use bilimal\common\components\ActiveRecord;

class CollegeInfo extends ActiveRecord
{

    use ServerDbConnectionTrait;

    public static function tableName()
    {
        return 'college_process.college_info';
    }

}