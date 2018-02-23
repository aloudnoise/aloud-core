<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 13.12.2017
 * Time: 23:58
 */

namespace app\modules\tests\models\constructor;


use app\components\VarDumper;
use common\components\Model;
use common\helpers\Common;

class TextImport extends Base
{

    public $import = null;
    public $parsed = null;
    public $count = null;
    public $failed = null;

    public function fields()
    {
        return ['import', 'parsed', 'count', 'failed'];
    }

    public function rules()
    {
        return [
            [['import','parsed'], 'string'],
            [['theme'], 'safe'],
            [['theme_id'], 'integer'],
            [['parsed'], 'filter', 'filter' => function($value) {
                return !is_array($value) ? json_decode($value, true) : $value;
            }]
        ];
    }

    public function getReturn($action = null)
    {
        if ($action == "parse") {
            return $this->toArray();
        }
        return null;
    }

    public function save()
    {

        if (!$this->validate()) return false;

        if ($this->parsed) {
            return $this->saveParsed();
        }

        if ($this->import) {
            return $this->parseText();
        }

        return false;

    }

    public function parseText()
    {
        $found = null;
        preg_match_all('/[0-9]{1,3}\)?\.?(.*)\n*\s*(\+?)\s*.*?(?:A|А)\)\.?(.*)\n*\s*(\+?)\s*.*?(?:B|Б|В)\)\.?(.*)\n*(?:\s*(\+?)\s*.*?(?:C|В|С)\)\.?(.*)\n*)?(?:\s*(\+?)\s*.*?(?:D|Г)\)\.?(.*)\n*)?(?:\s*(\+?)\s*.*?(?:E|Д|Е)\)\.?(.*)\n*)?/',
            $this->import,
            $found,PREG_SET_ORDER);

        $this->failed = nl2br(trim(preg_replace('/[0-9]{1,3}\)?\.?(.*)\n*\s*(\+?)\s*.*?(?:A|А)\)\.?(.*)\n*\s*(\+?)\s*.*?(?:B|Б|В)\)\.?(.*)\n*(?:\s*(\+?)\s*.*?(?:C|В|С)\)\.?(.*)\n*)?(?:\s*(\+?)\s*.*?(?:D|Г)\)\.?(.*)\n*)?(?:\s*(\+?)\s*.*?(?:E|Д|Е)\)\.?(.*)\n*)?/', '', $this->import)));

        $this->count = count($found);
        $questions = [];
        for($i=0; $i < $this->count; $i++){

            $question = [
                "name" => trim($found[$i][1]),
                "answers" => []
            ];

            for($d = 0;$d<5;$d++){

                $z = array('3','5','7','9','11');

                if(empty($found[$i][$z[$d]])){
                    continue;
                }

                $answer = [
                    "name" => trim($found[$i][$z[$d]])
                ];
                if($found[$i][$z[$d]-1] == '+'){
                    $answer['is_correct'] = 1;
                }

                $question['answers'][] = $answer;

            }

            $questions[] = $question;
        }

        if (!$questions) {
            $this->addError("import", \Yii::t("main","Не удалось распознать вопросы"));
            return false;
        }

        $this->parsed = $questions;
        return true;
    }

    public function saveParsed()
    {

        $added = 0;
        foreach ($this->parsed as $question) {
            $simple_question = new SimpleQuestion();
            $simple_question->attributes = $question;
            $simple_question->test = $this->test;
            $simple_question->theme_id = $this->theme_id;

            if ($simple_question->save()) {
                $added++;
            }
        }
        \Yii::$app->session->addFlash("ok", \Yii::t("main","Добавлено {count} {questions}", [
            'count' => $added,
            'questions' => Common::multiplier($added, [
                \Yii::t("main","вопрос"),
                \Yii::t("main","вопроса"),
                \Yii::t("main","вопросов"),
            ])
        ]));
        return true;

    }

}