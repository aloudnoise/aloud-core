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
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\db\Exception;

class FileImport extends Base
{

    public $document = null;
    public $parsed = null;
    public $count = null;
    public $failed = null;

    public function fields()
    {
        return ['document', 'parsed', 'count', 'failed'];
    }

    public function rules()
    {
        return [
            [['document','parsed'], 'string'],
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

        if ($this->document) {
            return $this->parseDocument();
        }

        return false;

    }

    public function parseDocument()
    {


        $f = pathinfo($this->document);
        $file_name = sys_get_temp_dir()."/".$f['basename'];
        $temp = fopen($file_name, 'w');
        fwrite($temp, file_get_contents($this->document));

        try {
            $spreadsheet = IOFactory::load($file_name);
        } catch(Exception $e) {
            fclose($temp);
            die('Error loading file : '.$e->getMessage());
        }


        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        $questions = [];
        $question = [];
        $current_question = null;
        foreach ($data as $row) {

            $scol = 2;
            $cell = $row[0];

            if (is_numeric($cell)) {
                if ($current_question !== null) {
                    $questions[] = $question;
                }

                $current_question = $cell;

                $name = $row[1];
                if (strlen($row[$scol]) > 2) {
                    $scol = 3;
                    $name = [
                        "ru-RU" => $name,
                        "kk-KZ" => $row[2]
                    ];
                } else if (strlen($row[$scol+1]) > 2) {
                    $scol = 4;
                    $name = [
                        "ru-RU" => $name,
                        "kk-KZ" => $row[3]
                    ];
                }

                $weight = 1;
                if ($row[$scol] != "" AND is_numeric(str_replace(",",".",$row[$scol]))) {
                    $weight = str_replace(",",".",$row[$scol + 1]);
                } else {
                    if (is_numeric(str_replace(",",".",$row[$scol + 1]))) {
                        $weight = str_replace(",",".",$row[$scol + 1]);
                    }
                }

                $question = [
                    'name' => is_array($name) ? json_encode($name, JSON_UNESCAPED_UNICODE) : $name,
                    'weight' => $weight
                ];


            } else {

                if (mb_strlen($cell, 'UTF-8') >= 1) {
                    if ($current_question) {

                        $name = $row[1];
                        if (mb_strlen($row[2], 'UTF-8') > 2) {
                            $scol = 3;
                            $name = [
                                "ru-RU" => $name,
                                "kk-KZ" => $row[2]
                            ];
                        } else if (strlen($row[$scol + 1]) > 2) {
                            $scol = 4;
                            $name = [
                                "ru-RU" => $name,
                                "kk-KZ" => $row[3]
                            ];
                        }

                        $question['answers'][] = [
                            'name' => is_array($name) ? json_encode($name, JSON_UNESCAPED_UNICODE) : $name,
                            "is_correct" => trim($row[$scol]) != "" ? 1 : 0
                        ];
                    }
                }
            }

        }

        if ($current_question !== null) {
            $questions[] = $question;
        }

        fclose($temp);
        unlink(sys_get_temp_dir()."/".$f['basename']);

        $this->count = count($questions);
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