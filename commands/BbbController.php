<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 27.01.2018
 * Time: 0:34
 */

namespace commands;


use app\components\VarDumper;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use common\models\Materials;
use yii\console\Controller;
use yii\db\Expression;

class BbbController extends Controller
{

    public $organization_id = null;

    public function actionCheckMeetingsStatus()
    {

        $live_conferences = Materials::findUnScoped()
            ->andWhere([
                'type' => Materials::TYPE_CONFERENCE
            ])
            ->andWhere("info->>'is_live' = '1' AND info->>'is_over' = '0'")
            ->all();

        if ($live_conferences) {
            foreach ($live_conferences as $conf) {

                $this->organization_id = $conf->organization_id;

                $getMeetingInfoParams = new GetMeetingInfoParameters($conf->meetingId, $conf->moderator_pwd);
                $response = \Yii::$app->bbb->getMeetingInfo($getMeetingInfoParams);
                if ($response->getReturnCode() == 'FAILED') {
                    $conf->is_over = 1;
                    $conf->is_live = 0;
                    $conf->over_ts = (new \DateTime())->format('d.m.Y H:i:s');
                    $conf->save();
                } else {
                    if ((string)$response->getRawXml()->running == 'false') {
                        $conf->is_over = 1;
                        $conf->is_live = 0;
                        $conf->over_ts = (new \DateTime())->format('d.m.Y H:i:s');
                        $conf->save();
                    }
                }
            }
        }

    }

    public function actionGetRecords()
    {
        $conferences_without_records = Materials::findUnScoped()
            ->andWhere([
                'type' => Materials::TYPE_CONFERENCE
            ])
            ->andWhere("info->>'records' IS NULL AND info->>'is_over' = '1'")
            ->all();

        if ($conferences_without_records) {
            foreach ($conferences_without_records as $conf) {

                $this->organization_id = $conf->organization_id;

                $recordingParams = new GetRecordingsParameters();
                $recordingParams->setMeetingId($conf->meetingId);

                $response = \Yii::$app->bbb->getRecordings($recordingParams);

                if ($response->getReturnCode() == 'SUCCESS') {
                    $records = [];
                    if ($response->getRawXml()->recordings) {
                        foreach ($response->getRawXml()->recordings->recording as $recording) {
                            $url = $recording->playback->format->url;
                            $records[] = (string)$url;
                        }
                    }
                    if (!empty($records)) {
                        $conf->records = $records;
                    } else {
                        if (strtotime($conf->over_ts) + 1800 < time()) {
                            $conf->records = [];
                        }
                    }
                    $conf->save();
                }

            }
        }


    }

}