<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 16.02.2018
 * Time: 17:36
 */

namespace common\models\notifications;


use app\helpers\OrganizationUrl;
use app\helpers\Url;
use common\models\Events;
use common\models\From;
use common\models\materials\Conferences;
use common\models\relations\EventMaterial;

class ConferenceNotification extends BaseNotification
{

    const ACTION_BEGUN = 1;

    public function attributesToInfo()
    {
        return array_merge(parent::attributesToInfo(), ['conference_id']);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['conference_id'], 'integer'],
        ]);
    }

    public static function getType()
    {
        return static::TYPE_CONFERENCE;
    }

    public function save($runValidation = true, $attributeNames = null)
    {

        if ($this->channel(static::CHANNEL_SOCKET)) {

            $conference = Conferences::findUnScoped()->byPk($this->conference_id)->one();
            var_dump($conference->attributes);

            $events = Events::find()
                ->current()
                ->byOrganization($this->organization_id)
                ->joinWith([
                    'materials'
                ])
                ->andWhere([
                    'event_material.related_id' => $this->conference_id,
                ])
                ->all();

            if ($events) {
                foreach ($events as $event) {

                    $en = new EventNotification();
                    $en->organization_id = $this->organization_id;
                    $en->target_id = $event->id;
                    $en->target_type = static::TYPE_EVENT;
                    $en->icon = 'video-camera';
                    $en->name = $event->name;
                    $en->color = 'info';
                    $en->content = 'Начался вебинар "'.$conference->name.'"';
                    $en->url = Url::to(['/library/view', 'id' => $conference->id, 'from' => (new From(['event', $event->id, 'process']))->params, 'oid' => $event->organization_id]);
                    $en->action = 999;
                    $en->channels = '00001';
                    if (!$en->save()) {
                        var_dump($en->getErrors());
                    }

                }
            }
            return true;
        }

        if ($this->channel(static::CHANNEL_WEB)) {
            return parent::save($runValidation, $attributeNames);
        }
    }

}