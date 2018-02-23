<?php

namespace bilimal\web\models\version2;

use bilimal\web\models\version2User;
use Yii;

/**
 * This is the model class for table "person.person".
 *
 * @property int $id
 * @property string $create_ts
 * @property int $status
 * @property bool $is_deleted
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property string $birth_date
 * @property string $birth_place
 * @property int $sex
 * @property int $nationality_id
 * @property int $citizenship_id
 * @property string $iin
 * @property int $is_pluralist
 * @property int $country_id
 * @property int $city_id
 * @property int $street_id
 * @property int $street_registration_id
 * @property int $city_registration_id
 * @property int $country_registration_id
 * @property string $language
 * @property int $oid
 * @property int $alledu_id
 * @property int $alledu_server_id
 * @property int $pupil_id
 * @property int $owner_id
 * @property int $server_id
 */
class Person extends \bilimal\common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person.person';
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
            [['create_ts', 'birth_date'], 'safe'],
            [['status', 'sex', 'nationality_id', 'citizenship_id', 'is_pluralist', 'country_id', 'city_id', 'street_id', 'street_registration_id', 'city_registration_id', 'country_registration_id', 'oid', 'alledu_id', 'alledu_server_id', 'pupil_id', 'owner_id', 'server_id'], 'default', 'value' => null],
            [['status', 'sex', 'nationality_id', 'citizenship_id', 'is_pluralist', 'country_id', 'city_id', 'street_id', 'street_registration_id', 'city_registration_id', 'country_registration_id', 'oid', 'alledu_id', 'alledu_server_id', 'pupil_id', 'owner_id', 'server_id'], 'integer'],
            [['is_deleted'], 'boolean'],
            [['language'], 'required'],
            [['firstname', 'lastname', 'middlename'], 'string', 'max' => 100],
            [['birth_place'], 'string', 'max' => 255],
            [['iin'], 'string', 'max' => 12],
            [['language'], 'string', 'max' => 2],
        ];
    }

    public function getFio()
    {
        return $this->lastname." ".$this->firstname.($this->middlename ? " ".$this->middlename : "");
    }

    public function getBilimalUser()
    {
        return $this->hasOne(BilimalUser::className(), ['bilimal_user_id' => 'id']);
    }

}
