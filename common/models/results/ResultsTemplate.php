<?php

namespace common\models\results;

use common\traits\FromTrait;
use common\components\ActiveRecord;
use common\models\Users;

/**
 * This is the model class for table "results.test_results".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ts
 * @property string $info
 */
class ResultsTemplate extends \common\components\ActiveRecord
{

    use FromTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'results.test_results';
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ["id" => "user_id"]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['info'], 'safe']
        ];
    }

}
