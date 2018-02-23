<?php
$this->setTitle(Yii::t("main", "Задание"));
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
?>

<div class="action-content">

    <div class="white-block">
        <div class="row">
            <div class="col-auto align-self-center">
                <h5 class="">
                    <?=Yii::t("main","Задание")?>: <span class="text-info"><?=$model->name?></span>
                </h5>
            </div>
        </div>
    </div>

    <div class="white-block mt-1">

        <h5><?=Yii::t("main","Время на прохождение: <b class='text-danger'>{t_count}</b> минут", [
                "t_count" => $model->time
            ])?></h5>

        <div class="mt-4">
            <?php if ($assign AND $assign->infoJson['password'] AND !$assign->storedPassword) { ?>
                <form method="post">
                    <div class="form-group">
                        <label class="control-label text-danger"><?=Yii::t("main","Данный тест защищен паролем")?></label>
                        <input type="password" name="task_password" class="form-control password-input" placeholder="<?=Yii::t("main","Введите пароль")?>" />
                        <?php if ($assign->getErrors("password")) { ?>
                            <p class="text-danger mt-1"><?=$assign->getErrors("password")[0]?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary"><?=Yii::t("main","Подтвердить")?></button>
                        <a href="<?=\Yii::$app->request->get("return") ?: \app\helpers\OrganizationUrl::to(['/main/index'])?>" class="btn btn-warning"><?=Yii::t("main","Назад")?></a>
                    </div>
                </form>
            <?php } else { ?>
                <?php if (!$result) { ?>
                    <a confirm="<?=Yii::t("main","После подтверждения данного диалогового окна, начнется тестирование, вы готовы?")?>" href="<?=app\helpers\OrganizationUrl::to(array_merge(["/tasks/process"], \Yii::$app->request->get()))?>" class="btn btn-primary"><?=Yii::t("main","Начать")?></a>
                <?php } else { ?>
                    <a href="<?=app\helpers\OrganizationUrl::to(array_merge(["/tasks/process"], \Yii::$app->request->get()))?>" class="btn btn-primary"><?=Yii::t("main","Продолжить")?></a>
                <?php } ?>
                <a href="<?=\Yii::$app->request->get("return") ?: \app\helpers\OrganizationUrl::to(['/main/index'])?>" class="btn btn-warning"><?=Yii::t("main","Назад")?></a>
            <?php } ?>
        </div>
    </div>

</div>