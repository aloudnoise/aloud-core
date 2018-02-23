<?php
namespace bilimal\web\models\forms;

use bilimal\web\models\BilimalOrganization;
use bilimal\web\models\Organizations;
use common\components\Model;

class GroupsModelForm extends Model
{

    public static $_models = [
        'bilimal' => 'bilimal\web\models\version2\Division',
        'old_bilimal' => 'bilimal\web\models\old\Group',
        'regular' => 'app\models\Groups'
    ];

    public static function getModel()
    {

        if (\Yii::$app->user->can("teacher") AND \Yii::$app->user->identity->isBilimal) {
            $org = BilimalOrganization::find()->andWhere([
                'organization_id' => Organizations::getCurrentOrganizationId()
            ])->one();
            if ($org->institute_type == BilimalOrganization::TYPE_NEW_BILIMAL) {
                return self::$_models['bilimal'];
            } else {
                return self::$_models['old_bilimal'];
            }
        }
        return self::$_models['regular'];
    }

}