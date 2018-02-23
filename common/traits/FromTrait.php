<?php
namespace common\traits;

use common\components\ActiveQuery;
use common\models\CourseLessons;
use common\models\Events;
use common\models\From;

trait FromTrait
{

    /**
     * @param null $from
     * @return ActiveQuery
     * @throws \Exception
     */
    public static function findFrom($from = null) {
        $from = $from ?: From::instance();

        if (!$from->validate()) {
            throw new \Exception("WRONG HASH");
        }

        return static::find()->andWhere([
            'from' => $from->name,
            'from_id' => $from->id
        ]);
    }

    public $fromModel = null;

    public function beforeSave($insert)
    {
        if ($this->isNewRecord ) {
            $from = $this->fromModel ?: From::instance();
            if (!$from->validate()) {
                throw new \Exception("WRONG HASH");
            }

            $this->from = $from->name;
            $this->from_id = $from->id;
        }
        return parent::beforeSave($insert);

    }

    public function getLesson()
    {
        return $this->hasOne(CourseLessons::className(), ['id' => 'from_id']);
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'from_id']);
    }

    public function getFromModel()
    {
        return $this->from == "lesson" ? $this->lesson->course : $this->event;
    }

}