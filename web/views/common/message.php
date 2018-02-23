<div class="d-block message-item" message_id="<?=$message->id?>">

    <div class="row">
        <div class="col">
            <div class="user-profile">
                <?=\app\widgets\EProfile\EProfile::widget([
                    'model' => $user
                ])?>
            </div>
        </div>
        <div class="col-auto ml-auto">
            <p class="date text-muted">
                <small>
                    <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time" => $message->ts,
                        "formatType" => 1,
                    ]) ?>
                </small>
            </p>
        </div>
    </div>


    <div class="message-content pt-3 pb-1">

        <p style="margin-left:45px;"><?=nl2br($message->message)?></p>

    </div>

    <?php if (!$last) { ?>
        <hr />
    <?php } ?>

</div>