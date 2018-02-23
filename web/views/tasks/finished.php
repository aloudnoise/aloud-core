<?php
(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);
$this->setTitle(Yii::t("main","Задание завершено"), false);
?>
<div class="action-content">

	<div class="white-block">

        <h5 class="mb-4"><?=Yii::t("main","Задание завершено")?></h5>

        <?php if ($model->answer) { ?>
            <p class="mb-2"><?=Yii::t("main","Ваш ответ")?></p>
            <p><?=$model->answer?></p>
        <?php } else { ?>
            <div class="alert alert-danger"><?=Yii::t("main","Вы не успели ответить на задание")?></div>
        <?php } ?>

        <?php if ($model->answer AND $model->result) { ?>
            <div class="row mt-3">
                <div class="col col-auto align-self-end">
                    <h4 style="margin-bottom:2px;"><?=Yii::t("main","Оценка учителя:")?></h4>
                </div>
                <div class="col col-auto">
                    <h1 class="ml-3 text-<?=$model->resultTextColor?>"><?=$model->translatedResultText?></h1>
                </div>
                <div class="col-auto ml-auto">
                    <?=\app\widgets\EProfile\EProfile::widget([
                            'model' => $model->reviewer
                    ])?>
                </div>
            </div>

            <?php if ($model->note) { ?>
                <div class="mt-3">
                    <p class="mb-2"><?=Yii::t("main","Пояснение учителя")?></p>
                    <p><?=$model->note?></p>
                </div>
            <?php } ?>

        <?php } else if ($model->answer AND !$model->result) { ?>
            <div class="mt-3 mb-0 alert alert-info"><?=Yii::t("main","Учитель проверит ваш ответ и выставит оценку")?></div>
        <?php } ?>

        <?php if (!$model->answer AND $model->result !== null) { ?>
            <div class="row mt-3">
                <div class="col-auto align-self-center">
                    <h4 style="margin-bottom:2px;"><?=Yii::t("main","Результат:")?></h4>
                </div>
                <div class="col-auto">
                    <h1 class="ml-3 text-<?=$model->resultTextColor?>"><?=$model->translatedResultText?></h1>
                </div>
            </div>
        <?php } ?>

        <div class="mt-4">
            <div class="row">
                <div class="col-auto">
                    <p class="text-muted">
                        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => $model->ts,
                            "formatType" => 2
                        ]) ?>
                    </p>
                </div>
                <div class="col-auto">
                    <p class="text-muted"><?= Yii::t("main","Время прохождения")?>: <?=$model->processTime?></p>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?=Yii::$app->request->get("return") ? Yii::$app->request->get("return") : app\helpers\OrganizationUrl::to(["/main/index"])?>" class="btn btn-primary btn-lg" ><?=Yii::t("main","Вернуться")?></a>
        </div>
	</div>

</div>