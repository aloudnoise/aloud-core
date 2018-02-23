<?php

namespace bilimal\web\models\version2;

use Yii;

/**
 * This is the model class for table "link.person_division_link".
 *
 * @property int $person_id
 * @property int $division_id
 * @property int $institution_id
 * @property string $from_ts
 * @property string $to_ts
 * @property int $subject_id
 * @property string $person_type
 * @property int $status
 * @property int $type
 * @property bool $is_deleted
 * @property string $create_ts
 * @property int $index
 * @property int $period_id
 * @property int $position
 * @property int $parent_person_id
 * @property int $control_period_id
 * @property string $week_days
 * @property int $rating_system_id
 * @property string $counter
 */
class PersonDivisionLink extends \bilimal\common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link.person_division_link';
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
            [['person_id', 'division_id', 'institution_id', 'from_ts', 'person_type', 'index'], 'required'],
            [['person_id', 'division_id', 'institution_id', 'subject_id', 'status', 'type', 'index', 'period_id', 'position', 'parent_person_id', 'control_period_id', 'rating_system_id'], 'default', 'value' => null],
            [['person_id', 'division_id', 'institution_id', 'subject_id', 'status', 'type', 'index', 'period_id', 'position', 'parent_person_id', 'control_period_id', 'rating_system_id'], 'integer'],
            [['from_ts', 'to_ts', 'create_ts'], 'safe'],
            [['is_deleted'], 'boolean'],
            [['week_days', 'counter'], 'string'],
            [['person_type'], 'string', 'max' => 100],
            [['person_id', 'division_id', 'institution_id', 'person_type', 'index'], 'unique', 'targetAttribute' => ['person_id', 'division_id', 'institution_id', 'person_type', 'index']],
        ];
    }

    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['id' => 'division_id']);
    }

}
