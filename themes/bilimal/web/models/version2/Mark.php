<?php

namespace bilimal\web\models\version2;

use common\models\Organizations;
use Yii;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\web\HeaderCollection;

/**
 * This is the model class for table "educational_process.mark".
 *
 * @property int $id
 * @property int $lesson_id
 * @property int $pupil_id
 * @property int $teacher_id
 * @property int $value
 * @property string $comment
 * @property int $institution_id
 * @property int $division_id
 * @property string $create_ts
 * @property bool $is_deleted
 * @property int $status
 * @property int $type
 * @property int $subject_id
 * @property string $rules
 * @property string $date
 * @property int $oid
 * @property int $server_id
 * @property string $partition_date
 */
class Mark extends \bilimal\common\components\ActiveRecord
{

    const TYPE_TEST = 26; //тестирование

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'educational_process.mark';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_bilimal');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lesson_id', 'pupil_id', 'teacher_id', 'value', 'institution_id', 'division_id', 'status', 'type', 'subject_id', 'oid', 'server_id'], 'integer'],
            [['pupil_id', 'teacher_id', 'value', 'institution_id', 'type', 'date'], 'required'],
            [['date', 'partition_date'], 'safe'],
            [['rules'], 'string'],
            [['comment'], 'string', 'max' => 100],
        ];
    }

    public static function addMark($test_result)
    {

        $client = new Client();

        $headers = new HeaderCollection();
        $headers->add('Authorization', 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjYyMjA4MDAwMCwiaXNzIjoiIiwiYXVkIjoiIiwiaWF0IjoxNTAyNTM4MTczLCJuYmYiOjE1MDI1MzgxNzMsInVpZCI6LTUyMDF9.oAaPgbbh4OKv8ccbg7WjO_ZOPjHtlF_TWyr4PB4omg0');
        $headers->add('INSTITUTION-ID', Organizations::getCurrentOrganization()->bilimalOrganization->institution_id);

        $lesson = Lesson::find()->byPk($test_result->source_id)->one();



        if ($lesson) {
            $response = $client->createRequest()
                ->setMethod("post")
                ->setUrl("https://v2.bilimal.kz/v2/educational-process/mark")
                ->setData([
                    "lesson_id" => $test_result->source_id,
                    "teacher_id" => 0,
                    "type" => self::TYPE_TEST,
                    "pupil_id" => \Yii::$app->user->identity->bilimalUser->bilimal_user_id,
                    "value" => $test_result->result,
                    "division_id" => $lesson->division_id,
                    "institution_id" => Organizations::getCurrentOrganization()->bilimalOrganization->institution_id,
                    "rules" => [
                        $test_result->result => [
                            'start' => $test_result->result
                        ]
                    ],
                    "subject_id" => $lesson->subject_id,
                    'comment' => 'Результат тест "' . $test_result->test->name . '"'
                ])
                ->setHeaders($headers)
                ->send();



            if ($response->isOk) {
                return true;
            } else {
                var_dump($response->data);
                die();
            }
        }
        return false;

    }

}
