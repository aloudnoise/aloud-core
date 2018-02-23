<?php
namespace bilimal\common\models\relations;

use bilimal\web\models\Organizations;

class EventOrganization extends \common\models\relations\EventOrganization
{

    public function save($runValidation = true, $attributeNames = null)
    {

        $transaction = \Yii::$app->db->beginTransaction();

        //creating organization from server;
        $organization = Organizations::createOrganizationFromServer($this->related_id, $transaction, $this);
        if (!$organization) {
            return false;
        }

        if (!\common\models\relations\EventOrganization::find()->andWhere([
            'related_id' => $organization->id,
            'target_id' => $this->target_id
        ])->exists()) {

            $this->related_id = $organization->id;
            if (parent::save($runValidation, $attributeNames)) {
                $transaction->commit();
                return true;
            }
        }

        $transaction->rollBack();
        return false;
    }

}