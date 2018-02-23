<?php
$this->setTitle($model->name);
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
?>

<div class="action-content">

	<div class="white-block mb-4">
        <div class="row">
            <div class="col">
                <h5 class="text-info">
                    <?=$model->name?>
                </h5>
            </div>
            <?php if ($model->canEdit) { ?>
                <div class="col-auto ml-auto">
                    <div class="btn-group">
                        <a  target="_full" href="<?=app\helpers\OrganizationUrl::to(["/tests/constructor/compile", "id" => $model->id])?>" class="btn btn-success"><?=Yii::t("main","Конструктор")?></a>
                        <a href="<?=app\helpers\OrganizationUrl::to(["/tests/base/add", "id" => $model->id])?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=app\helpers\OrganizationUrl::to(["/tests/base/delete", "id" => $model->id])?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="mt-2">
            <p>
                <span class="inline-block" style="margin-right:15px;">
                    <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time" => $model->ts,
                        "formatType" => 2
                    ]) ?>
                </span>
                <span class="inline-block" style="margin-right:15px;">
                    <?= Yii::t("main","Вопросов: ")?> <?=$model->qcount?>
                </span>
                <span class="inline-block" style="margin-right:15px;">
                    <?= Yii::t("main","Время: ")?> <?=$model->time?>м.
                </span>
                <span class="inline-block" style="margin-right:15px;">
                    <?= Yii::t("main","Порядок вопросов: ")?> <?=$model->random ? Yii::t("main","случайный") : Yii::t("main","статичный")?>
                </span>
            </p>
        </div>

        <?php if (!$result) { ?>
            <a href="<?=app\helpers\OrganizationUrl::to(["/tests/process/begin",
                'id' => $model->id,
                'from' => (new \common\models\From(['testing', $model->id, 'process']))->params
            ])?>" class="mt-3 btn btn-warning"><?=Yii::t("main","Пробное тестирование")?></a>
        <?php } else {?>
            <h3 class="mt-3"><?=Yii::t("main","Ваш результат пробного тестирования: {ball}%", [
                    "ball" => "<span class='".$result->resultTextColor."'>".$result->result."</span>"
                ])?> </h3>
            <a href="<?=app\helpers\OrganizationUrl::to(["/tests/process/delete", 'id' => $result->id])?>" class="mt-3 btn btn-danger"><?=Yii::t("main","Сбросить результат")?></a>
        <?php } ?>
    </div>

    <?php if ($model->themes) { ?>
        <div class="text-center mb-4" style="margin-left:-15px; margin-right:-15px;">
            <h5 class="text-muted"><?=Yii::t("main","Темы")?></h5>
        </div>
        <div class="white-block mb-3">
            <?php
            $n = 1;
            foreach ($model->themes as $th) { ?>
                <div class="constructor-theme position-relative">
                    <?=$this->render("@app/modules/tests/views/common/test_theme", [
                        'test_theme' => $th,
                        'n' => $n
                    ])?>
                    <?php if ($n != count($model->themes)) { ?>
                        <hr />
                    <?php } ?>
                </div>
            <?php $n++; } ?>
        </div>
    <?php } ?>

    <?php if ($model->questions) { ?>
        <div class="text-center mb-4" style="margin-left:-15px; margin-right:-15px;">
            <h5 class="text-muted"><?=Yii::t("main","Вопросы")?></h5>
        </div>
        <div class="white-block">
            <?php
            $n = 1;
            foreach ($model->questions as $question) {
                if ($question->question) { ?>
                    <div class="constructor-question position-relative">
                        <?=$this->render("@app/modules/tests/views/common/question", [
                            'question' => $question->question,
                            'n' => $n
                        ])?>
                        <?php if ($n != count($model->questions)) { ?>
                            <hr />
                        <?php } ?>
                    </div>
                <?php $n++; } ?>
            <?php } ?>
        </div>
    <?php } ?>

</div>