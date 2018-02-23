<?php

namespace bilimal\web\models\version2;

use common\models\Tests;
use Yii;

/**
 * This is the model class for table "educational_process.lesson".
 *
 * @property int $id
 * @property int $creator_person_id
 * @property int $status
 * @property string $create_ts
 * @property bool $is_deleted
 * @property int $institution_id
 * @property int $subject_id
 * @property int $division_id
 * @property string $date
 * @property int $index
 * @property int $person_id
 * @property int $rating_system_id
 * @property string $type
 * @property string $caption
 * @property int $oid
 * @property int $server_id
 * @property string $partition_date
 * @property int $test_id
 * @property int $group
 */
class Lesson extends \bilimal\common\components\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'educational_process.lesson';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_bilimal');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator_person_id', 'status', 'institution_id', 'subject_id', 'division_id', 'index', 'person_id', 'rating_system_id', 'oid', 'server_id', 'test_id', 'group'], 'default', 'value' => null],
            [['creator_person_id', 'status', 'institution_id', 'subject_id', 'division_id', 'index', 'person_id', 'rating_system_id', 'oid', 'server_id', 'test_id', 'group'], 'integer'],
            [['create_ts', 'date', 'partition_date'], 'safe'],
            [['is_deleted'], 'boolean'],
            [['subject_id', 'date', 'type'], 'required'],
            [['type'], 'string'],
            [['caption'], 'string', 'max' => 100],
        ];
    }

    public function getTestSourceName()
    {
        return "bilimal_lesson";
    }

    public function getTest()
    {
        return $this->hasOne(Tests::className(), ['id' => 'test_id']);
    }

}
