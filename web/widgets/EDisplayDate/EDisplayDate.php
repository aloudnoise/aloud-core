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
                            \Yii::t("main","w_minutes_1"),
                            \Yii::t("main","w_minutes_2"),
                            \Yii::t("main","w_minutes_3"),
                    ])." ".\Yii::t("main","w_before");
                }
                return \Yii::t("main","w_today_in").' '.$dt->format( 'H:i' );
            }
            elseif ( $labelTime == ( $dtc->format( 'd' ) - 1 ).'.'.$dtc->format( 'm.Y' ) ) {
                return \Yii::t("main","w_yesterday_in").' '.$dt->format( 'H:i' );
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
            '01'=>\Yii::t("main","w_january_in"),
            '02'=>\Yii::t("main","w_february_in"),
            '03'=>\Yii::t("main","w_march_in"),
            '04'=>\Yii::t("main","w_april_in"),
            '05'=>\Yii::t("main","w_may_in"),
            '06'=>\Yii::t("main","w_june_in"),
            '07'=>\Yii::t("main","w_july_in"),
            '08'=>\Yii::t("main","w_august_in"),
            '09'=>\Yii::t("main","w_september_in"),
            '10'=>\Yii::t("main","w_october_in"),
            '11'=>\Yii::t("main","w_november_in"),
            '12'=>\Yii::t("main","w_december_in")
        ];
    }

}
?>
