<?php

namespace bilimal\web\models\version2;

use Yii;

/**
 * This is the model class for table "organization.division".
 *
 * @property int $id
 * @property string $create_ts
 * @property int $status
 * @property string $caption
 * @property string $language
 * @property int $grade
 * @property int $institution_id
 * @property int $parent_id
 * @property int $subject_id
 * @property string $counter
 * @property int $estimation_type
 * @property string $places_info
 * @property int $training_type
 * @property bool $is_deleted
 * @property int $speciality_id
 * @property int $commission_id
 * @property int $oid
 * @property int $server_id
 * @property bool $grade_up_status
 * @property int $max_grade
 */
class Division extends \bilimal\common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization.division';
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
            [['create_ts'], 'safe'],
            [['status', 'grade', 'institution_id', 'parent_id', 'subject_id', 'estimation_type', 'training_type', 'speciality_id', 'commission_id', 'oid', 'server_id', 'max_grade'], 'integer'],
            [['language', 'grade', 'institution_id'], 'required'],
            [['counter', 'places_info'], 'string'],
            [['is_deleted', 'grade_up_status'], 'boolean'],
            [['caption'], 'string', 'max' => 100],
            [['language'], 'string', 'max' => 2],
        ];
    }

    public function getName()
    {
        return $this->caption;
    }

}
