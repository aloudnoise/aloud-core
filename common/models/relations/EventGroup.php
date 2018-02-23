<?php
namespace common\models\relations;

class EventGroup extends RelationsTemplate
{

    public $groupsModelForm = 'app\models\forms\GroupsModelForm';
    public function save($runValidation = true, $attributeNames = null)
    {
        $groupModel = ($this->groupsModelForm)::getModel();
        $group = $groupModel::find()->byPk($this->related_id)->one();
        if ($group->assignToEvent($this->target_id)) {
            return true;
        }
        return false;
    }

}