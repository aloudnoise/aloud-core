<?php
namespace bilimal\web\models\old;

use bilimal\web\models\LinksGenerator;
use bilimal\common\traits\ServerDbConnectionTrait;
use bilimal\common\components\ActiveRecord;
use common\models\relations\EventUser;

class Group extends ActiveRecord
{

    use ServerDbConnectionTrait;

    public static function tableName()
    {
        return 'college_process.group';
    }

    public function assignToEvent($event_id)
    {

        $pdls = Pupil::find()
            ->andWhere([
                'group_id' => $this->id,
                'is_deleted' => 0
            ])
            ->all();

        if ($pdls) {
            foreach ($pdls as $pdl) {

                $l = new LinksGenerator();
                if ($l->generateLinksOld($pdl, true, 3)) {

                    if (!EventUser::find()->andWhere([
                        'target_id' => $event_id,
                        'related_id' => $l->user->id
                    ])->exists()) {
                        $eu = new EventUser();
                        $eu->target_id = $event_id;
                        $eu->related_id = $l->user->id;

                        if (!$eu->save()) {
                            $this->addErrors($eu->getErrors());
                            return false;
                        }

                    }

                }

            }
            return true;
        }

        return false;

    }

}