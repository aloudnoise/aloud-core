<?php
namespace common\models;

use common\components\ActiveQuery;
use common\helpers\Common;

class Themes extends DicValues
{

    public static function tableName()
    {
        return "dic_values";
    }

    public function getQCount()
    {
        return $this->getQuestions()->count();
    }

    public function getQuestions()
    {
        return $this->hasMany(Questions::className(), ["theme_id" => "id"]);
    }

    public function getNameWithQCount()
    {
        return $this->name." (".\Yii::t("main","{count} {questions}", [
                "count" => count($this->questions),
                "questions" => Common::multiplier(count($this->questions), [
                    \Yii::t("main","вопрос"),
                    \Yii::t("main","вопроса"),
                    \Yii::t("main","вопросов"),
                ])
            ]).")";
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        $query = new ActiveQuery(get_called_class());
        return $query->andWhere([
            "dic" => "DicQuestionThemes"
        ]);
    }

}
?>