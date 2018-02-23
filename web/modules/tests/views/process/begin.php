<?php
$this->setTitle(Yii::t("main","Тест"), false);
?>
<div class="action-content">
    <div class="white-block">
        <div class="page-header"><h2 class="text-primary"><?=$model->name?></h2></div>

        <?php if ($model->description) { ?>
            <p class="mt-1 text-muted text-very-light-gray">
                <?=nl2br($model->description)?>
            </p>
        <?php } ?>
        <?php if (!empty($model->themes)) { ?>

            <h4 class="mt-3"><?=Yii::t("main","Темы:")?></h4>

            <?php foreach ($model->themes as $theme) { ?>
                <p class="mt-3">- <?=$theme->theme->name?></p>
            <?php } ?>

        <?php } ?>

        <div class="mt-2">
            <h5 ><?=Yii::t("main","Вопросов: <b class='text-danger'>{q_count}</b>", [
                    "q_count" => $model->qcount
                ])?></h5>
            <h5 class="mt-3"><?=Yii::t("main","Время на прохождение: <b class='text-danger'>{t_count}</b> минут", [
                    "t_count" => $model->time
                ])?></h5>
        </div>

        <div class="mt-3">
        <?php if ($assign AND $assign->infoJson['password'] AND !$assign->storedPassword) { ?>
            <form method="post">
                <div class="form-group">
                    <label class="control-label text-danger"><?=Yii::t("main","Данный тест защищен паролем")?></label>
                    <input type="password" name="test_password" class="form-control password-input" placeholder="<?=Yii::t("main","Введите пароль")?>" />
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
            <?php if (!$result OR ($result->finished AND $model->is_repeatable)) { ?>
                <a confirm="<?=Yii::t("main","После подтверждения данного диалогового окна, начнется тестирование, вы готовы?")?>" href="<?=app\helpers\OrganizationUrl::to(array_merge(["/tests/process/run"], \Yii::$app->request->get()))?>" class="btn btn-primary"><?=Yii::t("main","Начать")?></a>
            <?php } else { ?>
                <a href="<?=app\helpers\OrganizationUrl::to(array_merge(["/tests/process/run"], \Yii::$app->request->get()))?>" class="btn btn-primary"><?=Yii::t("main","Продолжить")?></a>
            <?php } ?>
            <a href="<?=\Yii::$app->request->get("return") ?: \app\helpers\OrganizationUrl::to(['/main/index'])?>" class="btn btn-warning"><?=Yii::t("main","Назад")?></a>
        <?php } ?>
        </div>

    </div>
</div>