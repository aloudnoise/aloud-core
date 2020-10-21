<?php
namespace aloud_core\common\helpers;

class Common {

    public static function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public static function multiplier($value, $words = array())
    {
        if ($value % 10 == 1 && ($value<10 || $value>20)) {
            return $words[0];
        } else if ($value % 10 > 1 && $value % 10 < 5 && ($value<10 || $value>20)) {
            return $words[1];
        } else {
            return $words[2];
        }
    }

    public static function translitIt($str)
    {
        $tr = array(
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Д' => 'D', 'Е' =>'E','Ж'=>'J','З'=>'Z','И'=>'I',
            'Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
            'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T',
            'У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'TS','Ч'=>'CH',
            'Ш'=>'SH','Щ'=>'SCH','Ъ'=>'','Ы'=>'YI','Ь'=>'',
            'Э'=>'E','Ю'=>'YU','Я'=>'YA','а'=>'a','б'=>'b',
            'в'=>'v','г'=>'g','д'=>'d','е'=>'e','ж'=>'j',
            'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l',
            'м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r',
            'с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h',
            'ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'y',
            'ы'=>'yi','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',' '=>'_'
        );
        return strtr($str,$tr);
    }

    public static function days_in_month($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public static function byLang($value, $language = null) {

        $language = $language ?: \Yii::$app->language;
        $data = !is_array($value) ? json_decode($value, true) : $value;
        if (is_array($data)) {
        	if (!empty($data[$language])) {
        		return $data[$language];
			} else {
        		foreach ($data as $d) {
        			if (!empty($d)) {
        				return $d;
					}
				}
				return $d;
			}
        }
        return $value;

    }

}