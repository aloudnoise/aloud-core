<?php
namespace app\models;

use app\models\relations\EventUser;
use common\models\relations\GroupUser;

class Groups extends \common\models\Groups
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t("main","Название группы"),
            'description' => \Yii::t("main","Описание"),
            'ts' => 'Ts',
            'is_deleted' => 'Is Deleted',
            'info' => 'Info',
        ];
    }

    public function assignToEvent($event_id)
    {

        $pdls = GroupUser::find()
            ->byOrganization()
            ->andWhere([
                'target_id' => $this->id
            ])
            ->all();

        if ($pdls) {
            foreach ($pdls as $pdl) {

                if (!EventUser::find()->andWhere([
                    'target_id' => $event_id,
                    'related_id' => $pdl->related_id
                ])->exists()) {
                    $eu = new EventUser();
                    $eu->target_id = $event_id;
                    $eu->related_id = $pdl->related_id;

                    if (!$eu->save()) {
                        $this->addErrors($eu->getErrors());
                        return false;
                    }

                }

            }
            return true;
        }

        return false;

    }

}