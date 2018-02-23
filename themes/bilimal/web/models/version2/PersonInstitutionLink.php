<?php

namespace bilimal\web\models\version2;

use Yii;

/**
 * This is the model class for table "link.person_institution_link".
 *
 * @property int $person_id
 * @property int $institution_id
 * @property string $from_ts
 * @property string $to_ts
 * @property int $status
 * @property string $person_type
 * @property int $index
 * @property bool $is_deleted
 * @property string $create_ts
 * @property string $position
 * @property string $documents_submission_date
 * @property string $comment
 * @property int $condition
 *
 * @property Institution $institution
 *
 */
class PersonInstitutionLink extends \bilimal\common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link.person_institution_link';
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
            [['person_id', 'institution_id', 'person_type', 'index'], 'required'],
            [['person_id', 'institution_id', 'status', 'index', 'condition'], 'default', 'value' => null],
            [['person_id', 'institution_id', 'status', 'index', 'condition'], 'integer'],
            [['from_ts', 'to_ts', 'create_ts', 'documents_submission_date'], 'safe'],
            [['is_deleted'], 'boolean'],
            [['person_type', 'position'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 255],
            [['person_id', 'institution_id', 'person_type', 'index'], 'unique', 'targetAttribute' => ['person_id', 'institution_id', 'person_type', 'index']],
        ];
    }

    public function getInstitution()
    {
        return $this->hasOne(Institution::className(), ['id' => 'institution_id']);
    }

}
