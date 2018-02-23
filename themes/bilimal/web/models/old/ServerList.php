<?php

namespace bilimal\web\models\old;
use bilimal\web\models\BilimalOrganization;
use common\components\ActiveQuery;
use bilimal\common\components\ActiveRecord;
use common\models\Organizations;


/**
 * This is the model class for table "system.server_list".
 *
 * @property integer $id
 * @property string $name
 * @property string $domain
 * @property string $user
 * @property string $password
 * @property string $db_name
 * @property string $caption
 * @property integer $region_id
 * @property integer $type
 * @property integer $province_id
 * @property integer $asu_type
 * @property integer $is_deleted
 * @property integer $is_active
 * @property string $subdomain
 * @property string $theme
 * @property integer $org_type
 * @property integer $eduorg_type
 * @property integer $is_test
 * @property integer $is_bbj
 */
class ServerList extends ActiveRecord
{

    public static function getDb()
    {
        return \Yii::$app->db_bilimal_old;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system.server_list';
    }

    public static function find()
    {
        $q = new ActiveQuery(get_called_class());
        $q->addSelect([
            "*",
            "caption" => "server_list.\"caption\"[1]"
        ]);
        return $q;
    }

    public static $_serverId = null;
    public static function getServerId()
    {
        if (!self::$_serverId) {
            $org = BilimalOrganization::find()->andWhere([
                'organization_id' => Organizations::getCurrentOrganizationId()
            ])->one();
            if ($org AND $org->institute_type == BilimalOrganization::TYPE_OLD_BILIMAL) {
                self::$_serverId = $org->institution_id;
            }

        }

        return self::$_serverId;
    }

    public function getTypeCaption()
    {
        $captions = [
            1 => \Yii::t("main","Школа"),
            2 => \Yii::t("main","Колледж"),
        ];
        return $captions[$this->type];
    }


}
