<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 07.08.2017
 * Time: 18:19
 */

namespace common\traits;


use app\helpers\ArrayHelper;
use common\models\Tags;

trait TagsTrait
{

    protected $_tagsString = false;

    public function getTags()
    {
        return $this->hasMany(Tags::className(), ["record_id"=>"id"])->andOnCondition([
            "table" => $this->tableName()
        ]);
    }

    public function setTagsString($string)
    {
        $this->_tagsString = $string;
    }

    public function getTagsString()
    {
        if (empty($this->_tagsString)) {
            $tags = $this->tags;
            $this->setTagsString(implode(", ", array_keys(ArrayHelper::map($tags, "name","name"))));
        }
        return $this->_tagsString;
    }

    public function afterSave($insert, $changedAttributes)
    {

        if ($this->_tagsString) {
            $tags = $this->_tagsString;
            if ($tags !== null) {

                $tags = explode(",", $tags);
                \Yii::$app->db->createCommand()->delete("tags",
                    [
                        "table" => $this->tableName(),
                        "record_id" => $this->id
                    ])->execute();

                if ($tags) {
                    foreach ($tags as $t) {
                        $t = trim($t);
                        if (!empty($t)) {
                            $tag = new Tags();
                            $tag->name = $t;
                            $tag->table = $this->tableName();
                            $tag->record_id = $this->id;
                            $tag->save();
                        }
                    }
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

}