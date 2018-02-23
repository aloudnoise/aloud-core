<?php $this->setTitle(Yii::t("main","Конструктор вопросов"))?>
<?php (Yii::$app->assetManager->getBundle("ckeditor"))::register($this); ?>
<?php
echo \app\widgets\EUploader\EUploader::widget([
    "standalone" => true
]);
(Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
?>
<div class="action-content">

    <?php if ($test) { ?>

        <div class="white-block mb-2 border-warning">
            <div class="row">
                <div class="col">
                    <h4><?=$test->name?></h4>
                </div>
                <div class="col col-auto ml-auto align-self-center">
                    <span class="inline-block" style="margin-right:15px;">
                        <?= Yii::t("main","Вопросов: ")?> <?=$test->qcount?>
                    </span>
                    <span class="inline-block" style="margin-right:15px;">
                        <?= Yii::t("main","Время: ")?> <?=$test->time?>м.
                    </span>
                    <span class="inline-block">
                        <?= Yii::t("main","Порядок вопросов: ")?> <?=$test->random ? Yii::t("main","случайный") : Yii::t("main","статичный")?>
                    </span>
                </div>
            </div>
        </div>

    <?php } else if ($theme) { ?>

        <div class="white-block mb-2 border-warning">
            <div class="row">
                <div class="col">
                    <h4><?=$theme->name?></h4>
                </div>
            </div>
        </div>

    <?php } ?>

    <div>

        <div class="row">

            <div class="col-3">

                    <?=$this->render("panel", [
                        'test' => $test,
                        'theme' => $theme,
                        'panel' => $panel
                    ])?>

            </div>

            <div class="col-9">

                <?php if (!$questions AND !$themes AND !$model) { ?>
                    <div class="alert alert-warning text-center mb-0 mt-0">
                        <h4><?=Yii::t("main","Пока не добавлено ни одного вопроса в {test_or_theme}, вы можете добавить вопросы, выбрав один из вариантов на панели инструментов слева", [
                                'test_or_theme' => $test ? Yii::t("main","тесте") : Yii::t("main","теме")
                            ])?></h4>
                    </div>
                <?php } ?>

                <?php if ($panel->instrument) { ?>

                    <div class="white-block mb-3">

                        <div class="instrument-form">
                            <?=$this->render("forms/".$panel->instrument, [
                                'model' => $model,
                                'panel' => $panel,
                                'questions' => $questions,
                                'test' => $test,
                                'theme' => $theme
                            ])?>
                        </div>

                    </div>

                <?php } ?>

                <?php if ($themes) { ?>

                    <div class="white-block border-warning">
                        <div class="text-center" style="margin-left:-15px; margin-right:-15px;">
                            <h5><?=Yii::t("main","Темы")?></h5>
                        </div>
                    </div>

                    <div class="themes-list mb-2">
                        <?php
                        $n = 1;
                        foreach ($themes as $th) { ?>
                            <?php if (Yii::$app->request->get("t_id") AND Yii::$app->request->get("t_id")==$th->id) { ?>
                                <div class="white-block instrument-form mt-2 mb-2">
                                    <?=$this->render("forms/full_theme", [
                                        'model' => $model,
                                        'panel' => $panel,
                                        'questions' => $questions,
                                        'themes' => $themes,
                                        'test' => $test,
                                        'theme' => $theme
                                    ])?>
                                </div>
                            <?php } else { ?>
                                <div class="constructor-theme position-relative white-block mt-1">
                                    <div class="actions-panel">
                                        <div class="row justify-content-end">
                                            <div class="col col-auto align-self-center">
                                                <div class="btn-group">
                                                    <a noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id, 't_id' => $th->id])?>" class="btn-sm btn btn-primary"><?=Yii::t("main","Изменить")?></a>
                                                    <a noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/unset', 'id' => $test->id, 'theme_id' => $theme->id, 't_id' => $th->id])?>" class="btn-sm btn btn-warning"><?=Yii::t("main","Открепить")?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?=$this->render("@app/modules/tests/views/common/test_theme", [
                                        'test_theme' => $th,
                                        'n' => $n
                                    ])?>
                                </div>
                            <?php } ?>
                        <?php $n++; } ?>
                    </div>
                <?php } ?>

                <?php if ($questions) { ?>

                    <div class="white-block border-warning">

                        <div class="text-center" style="margin-left:-15px; margin-right:-15px;">
                            <h5><?=Yii::t("main","Вопросы")?></h5>
                        </div>

                    </div>

                    <div class="questions-list mb-2">

                        <?php
                        $n = 1;
                        foreach ($questions as $question) { ?>
                            <?php if (Yii::$app->request->get("q_id") AND Yii::$app->request->get("q_id")==$question->id) { ?>
                                <div class="white-block instrument-form mt-2 mb-2">
                                    <?=$this->render("forms/simple_question", [
                                        'model' => $model,
                                        'panel' => $panel,
                                        'questions' => $questions,
                                        'test' => $test,
                                        'theme' => $theme
                                    ])?>
                                </div>
                            <?php } else { ?>
                                <div class="white-block mt-1 constructor-question position-relative">
                                    <div class="actions-panel">
                                        <div class="row justify-content-end">
                                            <div class="col col-auto align-self-center">
                                                <div class="btn-group">
                                                    <?php if ($question->user_id == Yii::$app->user->id) { ?>
                                                        <a noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id, 'q_id' => $question->id])?>" class="btn-sm btn btn-primary"><?=Yii::t("main","Изменить")?></a>
                                                    <?php } ?>
                                                    <a  noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/unset', 'id' => $test->id, 'theme_id' => $theme->id, 'q_id' => $question->id])?>" class="btn-sm btn btn-warning"><?=Yii::t("main","Открепить")?></a>
                                                    <?php if ($question->user_id == Yii::$app->user->id) { ?>
                                                        <a  noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/delete', 'id' => $test->id, 'theme_id' => $theme->id, 'q_id' => $question->id])?>" confirm="<?=Yii::t("main","Вы уверены? Вопрос будет полностью удален")?>" class="btn-sm btn btn-danger"><?=Yii::t("main","Удалить")?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?=$this->render("@app/modules/tests/views/common/question", [
                                        'question' => $question,
                                        'n' => $n
                                    ])?>
                                </div>
                            <?php } ?>
                        <?php $n++; } ?>
                    </div>
                <?php } ?>

            </div>

        </div>

    </div>

</div>