<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 18.02.2018
 * Time: 1:03
 */

namespace common\models\notifications;
use app\helpers\OrganizationUrl;
use app\helpers\Url;
use common\models\Events;
use common\models\Organizations;
use common\models\redis\OnlineUsers;
use common\models\relations\EventUser;
use common\models\Users;

/**

 *
 * Class EventNotification
 * @package common\models\notifications
 */
class EventNotification extends BaseNotification
{



    const ACTION_SHARED = 1;
    const ACTION_UNSHARED = 2;

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }

    public static function getType()
    {
        return static::TYPE_EVENT;
    }

    public $_event = null;
    public function beforeSave($insert)
    {

        if (!$this->icon){
            $this->icon = 'calendar';
        }

        if (!$this->content) {

            $this->_event = $this->_event ?: Events::find()->byPk($this->target_id)->one();

            if (!$this->name) {
                $this->name = $this->_event->name;
            }

            if (!$this->content) {
                if ($this->action == static::ACTION_SHARED) {
                    $this->content = 'Открыт общий доступ';
                    $this->color = 'success';
                }

                if ($this->action == static::ACTION_UNSHARED) {
                    $this->content = 'Закрыт общий доступ';
                    $this->color = 'danger';
                }
            }

        }

        if (!$this->url AND $this->action != static::ACTION_UNSHARED) {
            $this->url = Url::to(['/events/view', 'id' => $this->target_id, 'oid' => $this->organization_id]);
        }
        return parent::beforeSave($insert);
    }

    public function save($runValidation = true, $attributeNames = null)
    {

        if ($this->channel(static::CHANNEL_SOCKET)) {
            $this->beforeSave(true);

            $online_users = OnlineUsers::find()->andWhere([
                'organization_id' => $this->organization_id,
                'role' => Users::ROLE_PUPIL
            ])->andWhere([
                'between', 'ts', time() - 900, time() + 1
            ]);

            if (!in_array($this->action, [
                static::ACTION_SHARED,
                static::ACTION_UNSHARED
            ])) {

                $this->_event = $this->_event ?: Events::find()->byPk($this->target_id)->one();
                if ($this->_event->state != Events::STATE_SHARED) {

                    $uids = EventUser::find()
                        ->select(['related_id'])
                        ->byOrganization($this->organization_id)
                        ->andWhere([
                            'target_id' => $this->target_id
                        ])
                        ->asArray()->column();

                    $online_users->andWhere([
                        'in', 'user_id', $uids
                    ]);
                }

            }

            $online_users = $online_users->all();

            $this->socket($online_users);

            return true;
        }

        if ($this->channel(static::CHANNEL_WEB)) {
            return parent::save($runValidation, $attributeNames);
        }
    }

}