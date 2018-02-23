<div class="d-block chat-item" chat_id="<?=$chat->id?>">

    <div class="row">
        <div class="col">
            <?php if ($chat->type == \app\models\Chats::TYPE_CHAT) { ?>
                <div class="chat-profile"></div>
            <?php } else { ?>
                <div class="user-profile">
                    <?=\app\widgets\EProfile\EProfile::widget([
                        'model' => $chat->companion->user,
                        'link' => false
                    ])?>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php if ($message) { ?>
        <div style="margin-left:45px;" class="message-content pt-3 pb-1">
            <div class="row">
            <?php if ($message->user_id == Yii::$app->user->id) { ?>
                <div class="col-auto align-self-center text-muted font-weight-6 pr-4">
                    <?=Yii::t("main","Вы:")?>
                </div>
            <?php } ?>
                <div class="col">
                    <p class="p-2 bg-light"><?=nl2br($message->message)?></p>
                </div>
            </div>

        </div>
    <?php } ?>

    <div class="row mt-1">
        <?php if ($message) { ?>
            <div class="col-auto ml-auto">
                <p class="date text-muted">
                    <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time" => $message->ts,
                        "formatType" => 1,
                    ]) ?>
                </p>
            </div>
            <?php if ($new_messages) { ?>
                <div class="col-auto"><span class="text-danger new-count"><?=Yii::t("main","+ <c>{count}</c> {messages}", [
                            'count' => count($new_messages),
                            'messages' => \common\helpers\Common::multiplier(count($new_messages), [
                                Yii::t("main","сообщение"),
                                Yii::t("main","сообщения"),
                                Yii::t("main","сообщений"),
                            ])
                        ])?></span></div>
            <?php } ?>
        <?php } ?>
    </div>


</div>