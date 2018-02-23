<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 04.01.2018
 * Time: 1:09
 */

namespace app\models\forms;


use app\models\Chats;
use app\models\Messages;
use app\traits\BackboneRequestTrait;
use common\components\Model;
use common\models\relations\ChatUser;

class DialogForm extends Model
{

    public $id = null;

    public $user_id = null;
    public $message = null;

    public function rules()
    {
        return [
            [['user_id', 'message'], 'required'],
            [['user_id'], 'integer'],
            [['message'], 'string', 'max' => 2000]
        ];
    }

    public function save()
    {

        $transaction = \Yii::$app->db->beginTransaction();
        if (!$this->id) {
            $chats = Chats::find()
                ->joinWith([
                    'members'
                ])
                ->andWhere([
                    'chats.type' => Chats::TYPE_DIALOG
                ])
                ->andWhere([
                    'in', 'chat_user.related_id', [\Yii::$app->user->id]
                ])->all();

            $chat = null;
            foreach ($chats as $ch) {
                if ($ch->companion->related_id == $this->user_id) {
                    $chat = $ch;
                    break;
                }
            }

            if (!$chat) {
                $chat = new Chats();
                $chat->type = Chats::TYPE_DIALOG;
                if (!$chat->save()) {
                    $this->addErrors($chat->getErrors());
                    $transaction->rollBack();
                    return false;
                }

                foreach ([\Yii::$app->user->id, $this->user_id] as $uid) {
                    $member = new ChatUser();
                    $member->related_id = $uid;
                    $member->target_id = $chat->id;
                    $member->role = ChatUser::ROLE_MEMBER;
                    if (!$member->save()) {
                        $this->addErrors($member->getErrors());
                        $transaction->rollBack();
                        return false;
                    }
                }

            }
        } else {
            $chat = Chats::find()->byOrganization()->with([
                'members'
                ])->byPk($this->id)->one();
            if (!$chat->member) {
                return false;
            }
        }

        $message = new Messages();
        $message->message = $this->message;
        $message->chat_id = $chat->id;
        $message->user_id = \Yii::$app->user->id;
        if (!$message->save()) {
            $this->addErrors($message->getErrors());
            $transaction->rollBack();
            return false;
        }



        $json = json_encode(BackboneRequestTrait::arrayAttributes($message, [
            'user' => [
                'fields' => ['fio','photoUrl','roleName']
            ]
        ], array_merge((new Messages())->attributes()), true));
        foreach ($chat->members as $member) {
            if ($member->related_id != \Yii::$app->user->id) {
                \Yii::$app->redis->publish(Messages::getMessagesHash($member->related_id), $json);
                $new_messages = \Yii::$app->cache->get(Messages::getMessagesHash($member->related_id));
                if (!$new_messages) {
                    $new_messages = [];
                }
                $new_messages[$message->id] = [
                    'chat_id' => $message->chat_id,
                    'user_id' => $message->user_id
                ];
                \Yii::$app->cache->set(Messages::getMessagesHash($member->related_id), $new_messages);
            }
        }

        $this->id = $chat->id;
        $transaction->commit();
        return true;

    }

}