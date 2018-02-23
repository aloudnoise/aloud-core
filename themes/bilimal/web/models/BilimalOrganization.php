<?php

namespace bilimal\web\models;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "bilimal_organization".
 *
 * @property int $id
 * @property int $institution_id
 * @property int $institute_type
 * @property int $organization_id
 * @property string $ts
 * @property int $is_deleted
 */
class BilimalOrganization extends ActiveRecord
{

    const TYPE_NEW_BILIMAL = 1;
    const TYPE_OLD_BILIMAL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bilimal_organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['institution_id', 'organization_id'], 'required'],
            [['institution_id', 'organization_id', 'institute_type'], 'integer'],
        ];
    }

}
