<?php
namespace common\models\materials;

use common\models\Materials;

class Videos extends Materials
{

    public function attributesToInfo()
    {
        return ['video', 'is_live', 'live_date', 'live_time', 'is_over'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['is_live'], 'safe'],
            [['video'], 'safe'],
            [['video'], 'filter', 'filter' => function($value) {
                return !is_array($value) ? json_decode($value, true) : $value;
            }],
            [['type'], function() {
                if (empty($this->video)) {
                    $this->addError("info", \Yii::t("main", "Укажите ссылку на видео"));
                    return false;
                }
                return true;
            }, 'on' => self::SCENARIO_LIBRARY_SAVE],
            [['is_live'], 'filter', 'filter' => function($value) {
                return $value ? 1 : 0;
            }],
            [['live_date', 'live_time'], 'string'],
            [['live_date', 'live_time'], 'required', 'when' => function($model) {
                return $model->is_live;
            }],
            [['is_over'], 'integer'],
        ]);
    }

    public function getMaterialInfoString()
    {

        if ($this->video['type'] == 'youtube' && $this->is_live) {
            if ($this->is_over) return null;


            $key = 'AIzaSyDhbEbFW0Ct61pLQCRfcaGrlDy3hXDd43g'; //TODO: Ключь от Google API нужно будет поменять потом!!!!
            $id = $this->video['video_id'];

            $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails,liveStreamingDetails,recordingDetails,status&id=$id&key=$key";

            $data = @file_get_contents($url);
            $data = json_decode($data, true);
            if (!$data) return null;

            $string = '';

            switch ($data['items'][0]['snippet']['liveBroadcastContent']){
                case 'none':
                    $string .= 'Трансляция';
                    break;
                case 'upcoming':
                    $date = new \DateTime($data['items'][0]['liveStreamingDetails']['scheduledStartTime']);
                    $string .= 'Стрим заплапнирован на ' . $date->format('d m Y H:i:s');
                    break;
                case 'live':
                    $string .= 'LIVE';
                    break;
                default :
                    $string .= '';
            }

            return $string;

        }
        return '';
    }

}