<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 23.01.2018
 * Time: 0:44
 */

namespace common\models\materials;


use common\models\Materials;

class Ders extends Materials
{

    public function attributesToInfo()
    {
        return ['file', 'activity_id'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file','activity_id'], 'safe'],
            [['file'], 'filter', 'filter' => function($value) {
                return !is_array($value) ? json_decode($value, true) : $value;
            }],
            [['type'], function() {

                if (!in_array("/index.html", $this->file['zip_content']['files'])) {
                    $this->addError("info", \Yii::t("main","Не удается распознать ЦОР"));
                    return false;
                }

            }, 'on' => self::SCENARIO_LIBRARY_SAVE],
        ]);
    }

    public function getDerUrl()
    {
        $info = $this->infoJson['file'];
        if ($info AND $info['zip_content']) {
            $index_html = array_merge(array_filter($info['zip_content']['files'], function($a) {
                return $a == '/index.html';
            }));
            if ($index_html) {
                return $info['zip_content']['host'].$index_html[0];
            }
        }
        return "";
    }

}