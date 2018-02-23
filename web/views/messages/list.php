<?php if ($messages) { ?>
    <div class="chats-list">

        <?php foreach ($messages as $message) { ?>
            <div class="mb-3 white-block pointer wave-init" href="<?=\app\helpers\OrganizationUrl::to(['/messages/view', 'chat_id' => $message->chat_id])?>">
                <?=$this->render("@app/views/common/chat", [
                    'chat' => $message->chat,
                    'message' => $message,
                    'new_messages' => is_array($new_messages) ? array_filter($new_messages, function($m) use ($message) {
                        return $m['chat_id'] == $message->chat_id;
                    }) : [],
                ])?>
            </div>
        <?php } ?>

    </div>
<?php } else { ?>
    <div class="white-block mt-3">
        <div class="mb-0 alert alert-warning"><h5><?= Yii::t("main", "Диалогов не начато") ?></h5></div>
    </div>
<?php } ?>