<?php
$this->setTitle(Yii::t("main","Тесты"), false);
?>
<div class="action-content">

    <div class="row big-gutter">
        <div class="col-9">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Тесты")?></h3>
                </div>
                <div class="col col-auto ml-auto">
                    <a href="<?=app\helpers\OrganizationUrl::to(["/tests/base/add"])?>" class="btn btn-primary"><?=Yii::t("main","Добавить тест")?></a>
                </div>
            </div>

            <div class="mt-3 white-block filter-panel">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень тестов")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового теста, нажмите кнопку {add_link}, и заполните необходимые поля: название теста, описание, количество вопросов и прочее. К каждому тесту вы можете добавить вопросы, импортировать вопросы, либо прикрепить темы", [
                                'add_link' => "<a class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/tests/base/add"])."'>".Yii::t("main","Добавить тест")."</a>"
                            ])?></p>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Поиск тестов")?>" type="text" value="<?=$filter->search?>" class="find-input form-control" name="search" />
                    </div>
                    <div class="col-auto">
                        <a data-role="filter" data-action="submit" href="#" class="find-button btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                    </div>
                </div>

                <div class="row mt-3">
                    <?php if (\Yii::$app->user->can("base_teacher")) { ?>
                        <div class="col">
                            <div class="btn-group">
                                <?php
                                $get = \Yii::$app->request->get();
                                $get['filter']['pr'] = 1;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 1 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/tests/base/index"], $get))?>"><?=Yii::t("main","Мои тесты")?></a>
                                <?php
                                $get['filter']['pr'] = 2;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 2 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/tests/base/index"],$get))?>"><?=Yii::t("main","Все тесты")?></a>
                                <?php if (!Yii::$app->request->get("from")) { ?>
                                    <a class="font-weight-6 btn btn-light nav-link <?=$this->context->id == "questions" ? "text-warning" : "text-muted"?>" href="<?=app\helpers\OrganizationUrl::to(["/tests/questions/index"])?>"><?=Yii::t("main","Банк вопросов")?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>

            <?php if ($provider->totalCount > 0) { ?>
                <div class="tests-container">
                    <?php $tests = $provider->getModels(); ?>
                    <?php foreach ($tests as $t) { ?>
                        <div assign_item="test" assign_id="<?=$t->id?>" class="assign-item test-item white-block list-item mt-3">

                            <div class="row">
                                <div class="col-auto">
                                    <a href="<?=\app\helpers\OrganizationUrl::to(["/tests/base/view", "id" => $t->id])?>" class="icon-circle img-icon icon-circle-lg bg-warning"><i class="icon-12"></i></a>
                                </div>
                                <div class="col align-self-center pl-3">
                                    <h4 style=""><a href="<?=app\helpers\OrganizationUrl::to(["/tests/base/view", 'id' => $t->id])?>"><?php echo $t->name; ?></a></h4>
                                </div>
                                <?php if ($t->tagsString) { ?>
                                    <div class="col col-auto text-very-light-gray align-self-center">
                                        <i class="fa fa-tags"></i> <?= $t->tagsString?>
                                    </div>
                                <?php } ?>
                            </div>
                            <p class="text-muted mt-2">
                            <span class='inline-block' style="margin-right:10px;"><?=Yii::t("main","Вопросов: <b class='text-danger'>{q_count}</b>", [
                                "q_count" => $t->qcount
                            ])?></span>
                                <span class='inline-block' style="margin-right:10px;"><?=Yii::t("main","Время: <b class='text-danger'>{time}</b>", [
                                        "time" => $t->time."м."
                                    ])?></span>
                                <span class="inline-block">
                                    <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                        "time" => $t->ts,
                                        "formatType" => 2
                                    ]) ?>
                                </span>
                            </p>
                        </div>
                    <?php } ?>

                    <?= \app\widgets\EPager\EPager::widget([
                        'pagination' => $provider->pagination,
                    ]) ?>

                </div>

            <?php } else { ?>
                <div class="white-block mt-3">
                    <div class="mb-0 alert alert-warning"><h5>
                            <?php if ($filter->pr == 1) {
                                echo Yii::t("main", "Вы еще не создали ни одного теста");
                            } else {
                                echo Yii::t("main", "Тестов не найдено");
                            } ?>
                        </h5></div>
                </div>
            <?php } ?>

        </div>

        <div class="col-3">

            <div class="row">
                <div class="col">
                    <h3 class="mb-0 font-weight-4"><?=Yii::t("main","Фильтры")?></h3>
                </div>
            </div>

            <div class="mt-3 white-block filter-panel">

                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/tests/base/index'
                ])?>

            </div>

        </div>

    </div>


</div>