<?php
namespace api\models\results;

class TestResults extends \common\models\results\TestResults
{


    public function attributesForSave($scenario)
    {
        return ['question'];
    }

    public function rules()
    {
        return [
            [['question'], 'safe', 'on' => [self::SCENARIO_UPDATE]]
        ];
    }

    public function setQuestion($question)
    {
        $answers = $this->infoJson['answers'];
        $answers[$question['id']] = $question['answers'];
        $this->setInfo("answers", $answers);
    }

}