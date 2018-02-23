<?php
namespace api\models\forms;

use yii\db\Exception;

class QuestionsForm extends \common\models\forms\QuestionsForm
{

    public $parse_questions = null;
    public $type = null;


    public $parsed = null;

    public function rules()
    {
        return [
            [['type','parse_questions'], 'required']
        ];
    }

    public function save()
    {

        if (!$this->validate()) return false;

        if ($this->parse_questions AND $this->type == "text") {
            $this->parsed = $this->_parseText();
            return true;
        }

        $parsers = [
            "lms" => "_lmsParser"
        ];

        if (isset($parsers[SYSTEM])) {
            $this->parsed = $this->$parsers[SYSTEM]();
            return true;
        }
        $this->parsed = $this->_defaultParser();
        return true;

    }

    private function _parseText()
    {
        $found = null;
        preg_match_all('/[0-9]{1,3}\)?\.?(.*)\n*\s*(\+?)\s*.*?(?:A|А)\)\.?(.*)\n*\s*(\+?)\s*.*?(?:B|Б|В)\)\.?(.*)\n*(?:\s*(\+?)\s*.*?(?:C|В|С)\)\.?(.*)\n*)?(?:\s*(\+?)\s*.*?(?:D|Г)\)\.?(.*)\n*)?(?:\s*(\+?)\s*.*?(?:E|Д|Е)\)\.?(.*)\n*)?/',
            $this->parse_questions,
            $found,PREG_SET_ORDER);

        $count_questions = count($found);
        $questions = [];
        for($i=0; $i < $count_questions; $i++){

            $question = [
                "name" => trim($found[$i][1]),
                "answers" => []
            ];

            for($d = 0; $d<5;$d++){

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

        return $questions;
    }

    private function _defaultParser()
    {
        if ($this->parse_questions AND $this->type == "excel") {

            $url = $this->parse_questions;
            if (!$url) {
                throw new Exception("NO FILE URL");
            }

            $f = pathinfo($url);
            $temp = fopen(sys_get_temp_dir()."/".$f['basename'], 'w');
            fwrite($temp, file_get_contents($url));

            try {
                $inputFileType = \PHPExcel_IOFactory::identify(sys_get_temp_dir()."/".$f['basename']);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load(sys_get_temp_dir()."/".$f['basename']);
            } catch(Exception $e) {
                fclose($temp);
                die('Error loading file : '.$e->getMessage());
            }

            /* @var $objPHPExcel \PHPExcel */
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            $questions = [];
            $question = [];
            $current_question = null;
            for ($row = 0; $row <= $highestRow; $row++){

                $scol = 2;
                $cell = $sheet->getCellByColumnAndRow(0,$row);

                if (is_numeric($cell->getValue())) {
                    if ($current_question !== null) {
                        $questions[] = $question;
                    }

                    $current_question = $cell->getValue();

                    $name = $sheet->getCellByColumnAndRow(1,$row)->getValue();
                    if (strlen($sheet->getCellByColumnAndRow($scol,$row)->getValue()) > 2) {
                        $scol = 3;
                        $name = [
                            "ru-RU" => $name,
                            "kk-KZ" => $sheet->getCellByColumnAndRow(2,$row)->getValue()
                        ];
                    } else if (strlen($sheet->getCellByColumnAndRow($scol+1,$row)->getValue()) > 2) {
                        $scol = 4;
                        $name = [
                            "ru-RU" => $name,
                            "kk-KZ" => $sheet->getCellByColumnAndRow(3,$row)->getValue()
                        ];
                    }

                    $weight = 1;
                    if ($sheet->getCellByColumnAndRow($scol,$row)->getValue() != "" AND is_numeric(str_replace(",",".",$sheet->getCellByColumnAndRow($scol,$row)->getValue()))) {
                        $weight = str_replace(",",".",$sheet->getCellByColumnAndRow($scol + 1, $row)->getValue());
                    } else {
                        if (is_numeric(str_replace(",",".",$sheet->getCellByColumnAndRow($scol + 1, $row)->getValue()))) {
                            $weight = str_replace(",",".",$sheet->getCellByColumnAndRow($scol + 1, $row)->getValue());
                        }
                    }

                    $question = [
                        'name' => is_array($name) ? json_encode($name, JSON_UNESCAPED_UNICODE) : $name,
                        'weight' => $weight
                    ];


                } else {

                    if (mb_strlen($cell->getValue(), 'UTF-8') >= 1) {
                        if ($current_question) {

                            $name = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                            if (mb_strlen($sheet->getCellByColumnAndRow(2, $row)->getValue(), 'UTF-8') > 2) {
                                $scol = 3;
                                $name = [
                                    "ru-RU" => $name,
                                    "kk-KZ" => $sheet->getCellByColumnAndRow(2, $row)->getValue()
                                ];
                            } else if (strlen($sheet->getCellByColumnAndRow($scol+1,$row)->getValue()) > 2) {
                                $scol = 4;
                                $name = [
                                    "ru-RU" => $name,
                                    "kk-KZ" => $sheet->getCellByColumnAndRow(3,$row)->getValue()
                                ];
                            }

                            $question['answers'][] = [
                                'name' => is_array($name) ? json_encode($name, JSON_UNESCAPED_UNICODE) : $name,
                                "is_correct" => trim($sheet->getCellByColumnAndRow($scol, $row)->getValue()) != "" ? 1 : 0
                            ];
                        }
                    }
                }

            }

            if ($current_question !== null) {
                $questions[] = $question;
            }

            fclose($temp);
            return $questions;

        }
    }

    private function _lmsParser()
    {
        if ($this->parse_questions AND $this->type == "excel") {

            $langs = [
                'ru' => "ru-RU",
                "kz" => "kk-KZ"
            ];

            $url = $this->parse_questions;
            if (!$url) {
                throw new Exception("NO FILE URL");
            }

            $path = \Yii::getAlias("@webroot");
            include($path."/protected/vendors/PHPExcel/PHPExcel.php");

            $f = pathinfo($url);
            $temp = fopen(sys_get_temp_dir()."/".$f['basename'], 'w');
            fwrite($temp, file_get_contents($url));

            try {
                $inputFileType = \PHPExcel_IOFactory::identify(sys_get_temp_dir()."/".$f['basename']);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load(sys_get_temp_dir()."/".$f['basename']);
            } catch(Exception $e) {
                fclose($temp);
                die('Error loading file : '.$e->getMessage());
            }

            /* @var $objPHPExcel \PHPExcel */
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            $questions = [];
            $question = [];
            $current_question = null;

            $possible_question_hashes = [
                "#name",
                "#weight"
            ];

            $possible_answer_hashes = [
                "#name"
            ];

            $hashes = [];
            for ($row = 1; $row <= $highestRow; $row++){

                $highestColumn = $sheet->getHighestDataColumn($row);
                // Определяем хеши колонок в документе
                if ($row == 1) {

                    $highestColumn = $sheet->getHighestDataColumn($row);
                    $colNumber = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                    for ($col = 1; $col < $colNumber; $col++) {
                        $cell = $sheet->getCellByColumnAndRow($col,$row);
                        if (!empty($cell->getValue())) {
                            $cell = explode(":", $cell->getValue());
                            if (isset($cell[1]) AND $cell[1] == "lang") {
                                $hashes[$cell[0]] = [
                                    "col" => $col,
                                    "lang" => $langs[$cell[2]]
                                ];
                            }
                            if (count($cell) == 1) {
                                $hashes[$cell[0]] = $col;
                            }
                        }
                    }
                    continue;
                }

                $cell = $sheet->getCellByColumnAndRow(0, $row);

                if ($cell->getValue() == "#end") {
                    break;
                }

                if (is_numeric($cell->getValue())) {

                    if ($current_question !== null) {
                        $questions[] = $question;
                    }

                    $question = [];
                    $current_question = $cell->getValue();

                    foreach ($hashes as $hash => $col) {
                        if (in_array($hash, $possible_question_hashes)) {
                            $column_n = is_array($col) ? $col['col'] : $col;
                            $cell = $sheet->getCellByColumnAndRow($column_n, $row);

                            $value = $cell->getValue();
                            if ($value instanceof \PHPExcel_RichText)
                            {
                                $value = $value->getPlainText();
                            }

                            if (!empty($value)) {
                                if (isset($col['lang'])) {
                                    $question[trim($hash,"#")][$col['lang']] = $value;
                                } else {
                                    $question[trim($hash,"#")] = $value;
                                }
                            }
                        }
                    }

                    foreach ($question as $name => $value) {
                        if (is_array($value)) {
                            $question[$name] = json_encode($value, JSON_UNESCAPED_UNICODE);
                        }
                    }

                } else {

                    if ($current_question) {

                        $answer = [];
                        $answer['is_correct'] = $cell->getValue() == "+" ? 1 : 0;
                        foreach ($hashes as $hash => $col) {
                            if (in_array($hash, $possible_answer_hashes)) {
                                $column_n = is_array($col) ? $col['col'] : $col;
                                $cell = $sheet->getCellByColumnAndRow($column_n, $row);

                                $value = $cell->getValue();
                                if ($value instanceof \PHPExcel_RichText)
                                {
                                    $value = $value->getPlainText();
                                }

                                if (!empty($value)) {
                                    if (is_array($col)) {
                                        if (isset($col['lang'])) {
                                            $answer[trim($hash,"#")][$col['lang']] = $value;
                                        }
                                    } else {
                                        $answer[trim($hash,"#")] = $value;
                                    }
                                }
                            }
                        }

                        foreach ($answer as $name => $value) {
                            if (is_array($value)) {
                                $answer[$name] = json_encode($value, JSON_UNESCAPED_UNICODE);
                            }
                        }

                        $question['answers'][] = $answer;
                    }
                }

            }

            if ($current_question !== null) {
                $questions[] = $question;
            }

            fclose($temp);
            return $questions;

        }
    }

}