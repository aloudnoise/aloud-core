<?php

namespace bilimal\web\models\version2;

use Yii;

/**
 * This is the model class for table "organization.institution".
 *
 * @property int $id
 * @property string $caption
 * @property int $country_id
 * @property int $city_id
 * @property int $region_id
 * @property int $district_id
 * @property int $status
 * @property string $create_ts
 * @property int $parent_id
 * @property int $type
 * @property bool $is_deleted
 * @property int $oid
 * @property int $server_id
 * @property string $region_caption
 */
class Institution extends \bilimal\common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization.institution';
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
            [['caption', 'region_caption'], 'string'],
            [['country_id', 'city_id', 'region_id', 'district_id', 'status', 'parent_id', 'type', 'oid', 'server_id'], 'default', 'value' => null],
            [['country_id', 'city_id', 'region_id', 'district_id', 'status', 'parent_id', 'type', 'oid', 'server_id'], 'integer'],
            [['create_ts'], 'safe'],
            [['is_deleted'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'caption' => 'Caption',
            'country_id' => 'Country ID',
            'city_id' => 'City ID',
            'region_id' => 'Region ID',
            'district_id' => 'District ID',
            'status' => 'Status',
            'create_ts' => 'Create Ts',
            'parent_id' => 'Parent ID',
            'type' => 'Type',
            'is_deleted' => 'Is Deleted',
            'oid' => 'Oid',
            'server_id' => 'Server ID',
            'region_caption' => 'Region Caption',
        ];
    }
}
