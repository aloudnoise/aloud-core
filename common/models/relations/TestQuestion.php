<?php

namespace common\models\relations;

use common\models\Materials;
use common\models\Questions;
use common\models\Tests;

class TestQuestion extends RelationsTemplate
{

    public static function tableName()
    {
        return "relations.test_question";
    }

    public function getTest()
    {
        return $this->hasOne(Tests::className(), ["id"=>"target_id"]);
    }

    public function getQuestion()
    {
        return $this->hasOne(Questions::className(), ["id"=>"related_id"]);
    }

}

?>