<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 31.12.2017
 * Time: 16:04
 */

namespace app\controllers;


use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\models\Chats;
use app\models\filters\MessagesFilter;
use app\models\forms\DialogForm;
use app\models\Messages;
use app\models\Users;
use app\traits\BackboneRequestTrait;
use common\models\relations\ChatUser;
use yii\httpclient\Message;
use yii\web\MethodNotAllowedHttpException;

class MessagesController extends Controller
{

    public function actionIndex()
    {

        $filter = new MessagesFilter();
        $filter->attributes = \Yii::$app->request->get("filter", []);

        return $this->render("index", [
            "filter" => $filter,
        ]);
    }

    public function actionList()
    {

        $filter = new MessagesFilter();
        $filter->attributes = \Yii::$app->request->get("filter", []);

        $chats_ids = ChatUser::find()
            ->select(['target_id'])
            ->byOrganization()
            ->andWhere([
                'related_id' => \Yii::$app->user->id
            ]);

        $filter->applyFilter($chats_ids);
        $chats_ids->asArray()->column();

        $messages = Messages::find()
            ->byOrganization()
            ->with([
                'chat' => function($q) {
                    return $q->with([
                        'members.user'
                    ]);
                }
            ])
            ->from([
                'messages' => Messages::find()
                    ->select(['DISTINCT ON (chat_id) *'])
                    ->andWhere(['in', 'chat_id', $chats_ids])
            ])
            ->orderBy('ts DESC')
            ->all();

        $new_messages = \Yii::$app->cache->get(Messages::getMessagesHash(\Yii::$app->user->id));

        return $this->render("list", [
            "messages" => $messages,
            'new_messages' => $new_messages
        ]);
    }

    public function actionAdd()
    {

        $model = new DialogForm();
        if (\Yii::$app->request->get("related_id")) {
            $user = Users::find()->byPk(\Yii::$app->request->get("related_id"))->one();
            $model->user_id = \Yii::$app->request->get("related_id");
        }

        if (\Yii::$app->request->get("chat_id")) {
            $model->id = \Yii::$app->request->get("chat_id");
        }

        if (\Yii::$app->request->post("DialogForm")) {
            $model->attributes = \Yii::$app->request->post("DialogForm");
            if ($model->save()) {
                return $this->renderJSON([
                   'redirect' => OrganizationUrl::to(['/messages/view', 'chat_id' => $model->id])
                ]);
            }
            return $this->renderJSON($model->getErrors(), true);
        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['user_id', 'message'], true);

        return $this->render("form", [
            "user" => $user,
            "model" => $model
        ]);

    }

    public function actionView()
    {

        $model = new DialogForm();
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['message'], true);

        $chat = Chats::find()->byOrganization()->byPk(\Yii::$app->request->get("chat_id"))->one();
        if (!$chat OR !$chat->member) throw new MethodNotAllowedHttpException("NO CHAT");

        $messages = Messages::find()
            ->byOrganization()
            ->andWhere([
                'chat_id' => $chat->id
            ])
            ->orderBy('ts DESC')
            ->limit(15)
            ->all();

        if ($messages) {
            $messages = array_reverse($messages);
        }

        $new_messages = \Yii::$app->cache->get(Messages::getMessagesHash(\Yii::$app->user->id));
        if ($new_messages) {
            $new_messages = array_filter($new_messages, function ($m) use ($chat) {
                return $m['chat_id'] != $chat->id;
            });
        }
        \Yii::$app->cache->set(Messages::getMessagesHash(\Yii::$app->user->id), $new_messages);

        return $this->render("view", [
            "model" => $model,
            "chat" => $chat,
            'messages' => $messages
        ]);
    }

}