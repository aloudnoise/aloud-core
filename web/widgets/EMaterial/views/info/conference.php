<?php if ($this->context->material->is_live) { ?>

    <div class="alert alert-info">
        <h5><?=Yii::t("main","Вебинар уже начался")?></h5>
    </div>

    <?php if ($this->context->material->canEdit) { ?>
        <a href="<?=\app\helpers\OrganizationUrl::to(['/library/begin-conference', 'id' => $this->context->material->id])?>" class="btn btn-primary"><?=Yii::t("main","Вернутся в конференцию")?></a>
    <?php } else { ?>

        <?php if (\Yii::$app->user->isGuest) { ?>
            <form method="get" action="<?=\app\helpers\Url::to(['/library/join-conference'])?>">
                <input type="hidden" name="m" value="<?=$this->context->material->hash?>" />
                <div class="row">
                    <div class="col-auto">
                        <input style="width:400px;" class="form-control" type="text" name="fio" placeholder="<?=Yii::t("main","Укажите ваше ФИО")?>" />
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success" type="submit"><?=Yii::t("main","Принять участие")?></button>
                    </div>
                </div>
            </form>
        <?php } else { ?>
            <a href="<?=\app\helpers\OrganizationUrl::to(['/library/join-conference', 'm' => $this->context->material->hash])?>" class="btn btn-success"><?=Yii::t("main","Принять участие")?></a>
        <?php } ?>

    <?php } ?>

<?php } else if ($this->context->material->is_over) { ?>
    <div class="alert alert-warning">
        <h5><?=Yii::t("main","Вебинар окончен")?></h5>
    </div>

<!--    --><?php //if ($this->context->material->canEdit) { ?>
<!--        <div class="my-2">-->
<!--            <a href="--><?//=\app\helpers\OrganizationUrl::to(['/library/begin-conference', 'id' => $this->context->material->id])?><!--" class="btn btn-primary">--><?//=Yii::t("main","Начать еще раз")?><!--</a>-->
<!--        </div>-->
<!--    --><?php //} ?>

    <?php if ($this->context->material->records === null) { ?>
        <div class="alert alert-info">
            <h5><?=Yii::t("main","Запись пока не доступна")?></h5>
        </div>
    <?php } else if (empty($this->context->material->records)) { ?>
        <div class="alert alert-danger">
            <h5><?=Yii::t("main","Запись отсутствует")?></h5>
        </div>
    <?php } else { ?>
        <?php foreach ($this->context->material->records as $record) { ?>
            <div class="record mb-3">
                <a class="btn btn-warning der-fullscreen pointer text-white" style="margin-bottom:5px;"><?=Yii::t("main","Развернуть")?></a>
                <iframe class="der" src="<?=$record?>" style="width:100%;"></iframe>
                <a style="z-index:9999; position: fixed; bottom:20px; right:60px; display:none;" class="btn btn-danger btn-sm der-fullscreen-cancel  pointer text-white"><?=Yii::t("main","Свернуть")?></a>
            </div>
        <?php } ?>
    <?php } ?>

<?php } else { ?>

    <div class="alert alert-info">
        <h5><?=Yii::t("main","Вебинар начнется <span class='ml-3'>{date}</span>", [
                'date' => \app\widgets\EDisplayDate\EDisplayDate::widget([
                    'formatType' => 2,
                    'time' => $this->context->material->live_date." ".$this->context->material->live_time
                ])
            ])?></h5>
        <p class="mt-2">
            <?=Yii::t("main","Чтобы проверить статус вебинара, обновите страницу")?>
        </p>
    </div>

    <?php if ($this->context->material->canEdit) { ?>
        <a href="<?=\app\helpers\OrganizationUrl::to(['/library/begin-conference', 'id' => $this->context->material->id])?>" class="btn btn-primary"><?=Yii::t("main","Начать сейчас")?></a>
    <?php } ?>

<?php } ?>
