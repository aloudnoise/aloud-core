<?php

namespace bilimal\web\models\version2;

use Yii;

/**
 * This is the model class for table "person.person_credential".
 *
 * @property int $person_id
 * @property string $indentity
 * @property string $validation
 * @property int $status
 * @property string $create_ts
 * @property string $activation_code
 * @property string $auth_ts
 * @property string $name
 * @property bool $is_deleted
 *
 * @property Person $person
 *
 */
class PersonCredential extends \bilimal\common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person.person_credential';
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
            [['person_id', 'name'], 'required'],
            [['person_id', 'status'], 'default', 'value' => null],
            [['person_id', 'status'], 'integer'],
            [['create_ts', 'auth_ts'], 'safe'],
            [['is_deleted'], 'boolean'],
            [['indentity', 'name'], 'string', 'max' => 255],
            [['validation', 'activation_code'], 'string', 'max' => 2048],
            [['person_id', 'name'], 'unique', 'targetAttribute' => ['person_id', 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => 'Person ID',
            'indentity' => 'Indentity',
            'validation' => 'Validation',
            'status' => 'Status',
            'create_ts' => 'Create Ts',
            'activation_code' => 'Activation Code',
            'auth_ts' => 'Auth Ts',
            'name' => 'Name',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

}
