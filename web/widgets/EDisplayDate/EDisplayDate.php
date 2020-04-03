<?php

namespace aloud_core\web\widgets\EDisplayDate;

use aloud_core\web\components\Widget;

class EDisplayDate extends Widget
{

    public $assets_path = '@aloud_core/web/widgets/EDisplayDate/assets';
    public $js = [
        'EDisplayDate.js'
    ];

    public $time = 0;
    public $formatType =1; //type : 1 - 26 мая, 12.30;  2 26 мая в 12.30
    public $showTime = true;

    public function run()
    {
        $formatType=$this->formatType;
        if ($this->type != Widget::TYPE_TEMPLATE) {
            $label = $this->timeformat($this->time, $formatType);
        } else {
            $label = "<%=EDisplayDate(".$this->time.",".$formatType.")%>";
            $this->time = "<%=".$this->time."%>";
        }
        $templateType = '';
        switch($formatType) {
        	case 2: $templateType = "on_date"; break;
        	case 3: $templateType = "clean_date"; break;
        	default: $templateType = "posted_date";
        }
        return $this->render($templateType, ["label" => $label]);
        // if($formatType==1)
        //     return $this->render("posted_date", [
        //             "label"=>$label,
        //     ]);
        // else
        //     return $this->render("on_date", [
        //             "label"=>$label,
        //     ]);
    }


    public function timeformat($time, $formatType)
    {

        $tz = 'Asia/Almaty';

        $dt = new \DateTime($time, new \DateTimeZone($tz));

        $labelTime = $dt->format('d.m.Y');
        $arrM = $this->getArrM();

      	if($formatType==2 || $formatType==3)
        {
           return $dt->format('d').' '.$arrM[$dt->format('m')].' '.$dt->format('Y').' '.($this->showTime ? \Yii::t("main"," в ").$dt->format('H:i') : "");
        }
        else
        {


            $dtc = new \DateTime("now", new \DateTimeZone($tz));
            if ( $labelTime == $dtc->format("d.m.Y") ) {

                if (($dtc->getTimestamp() + $dtc->getOffset()) - ($dt->getTimestamp() + $dt->getOffset() ) < 60*60) {
                    $t = ceil((($dtc->getTimestamp() + $dtc->getOffset()) - ($dt->getTimestamp() + $dt->getOffset() ))/60);
                    return $t." ".\common\helpers\Common::multiplier($t, [
                            \Yii::t("main","минута"),
                            \Yii::t("main","минуты"),
                            \Yii::t("main","минут"),
                    ])." ".\Yii::t("main","назад");
                }
                return \Yii::t("main","сегодня в").' '.$dt->format( 'H:i' );
            }
            elseif ( $labelTime == ( $dtc->format( 'd' ) - 1 ).'.'.$dtc->format( 'm.Y' ) ) {
                return \Yii::t("main","вчера в").' '.$dt->format( 'H:i' );
            }
            elseif ( $dt->format( 'Y' ) == $dtc->format( 'Y' ) ) {
                return $dt->format( 'd' ).' '.$arrM[$dt->format( 'm' )].', '.$dt->format( 'H:i' );
            }
            else {
                return $dt->format( 'd' ).' '.$arrM[$dt->format( 'm' )].' '.$dt->format( 'Y' ).', '.$dt->format( 'H:i' );
            }
        }
    }

    public function getArrM()
    {
        return [
            '01'=>\Yii::t("main","января"),
            '02'=>\Yii::t("main","февраля"),
            '03'=>\Yii::t("main","марта"),
            '04'=>\Yii::t("main","апреля"),
            '05'=>\Yii::t("main","мая"),
            '06'=>\Yii::t("main","июня"),
            '07'=>\Yii::t("main","июля"),
            '08'=>\Yii::t("main","августа"),
            '09'=>\Yii::t("main","сентября"),
            '10'=>\Yii::t("main","октября"),
            '11'=>\Yii::t("main","ноября"),
            '12'=>\Yii::t("main","декабря")
        ];
    }

}
?>
