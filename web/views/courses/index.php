<?php
    $this->setTitle(Yii::t("main","Курсы обучения"), false);
?>

<div class="action-content">

    <div class="row big-gutter">
        <div class="col-9">
            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Курсы")?></h3>
                </div>
                <?php if (\Yii::$app->user->can("base_teacher") AND !\Yii::$app->request->get("assign")) { ?>
                    <div class="col col-auto ml-auto">
                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/courses/add"])?>" class="btn btn-primary"><?=Yii::t("main","Создать курс")?></a>
                    </div>
                <?php } ?>
            </div>

            <div class="mt-3 white-block">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень курсов")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового курса, нажмите кнопку {add_link}, и заполните необходимые поля: название курса, описание и прочее. К каждому курсу вы можете добавить уроки, прикрепить тесты, материалы и задания", [
                                'add_link' => "<a target='modal' class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/courses/add"])."'>".Yii::t("main","Создать курс")."</a>"
                            ])?></p>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Поиск курсов")?>" type="text" value="<?=$filter->search?>" class="find-input form-control" name="search" />
                    </div>
                    <div class="col col-auto">
                        <a href="#"  data-role="filter" data-action="submit" class="pointer btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
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
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 1 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/courses/index"], $get))?>"><?=Yii::t("main","Мои курсы")?></a>
                                <?php
                                $get['filter']['pr'] = 2;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 2 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/courses/index"],$get))?>"><?=Yii::t("main","Все курсы")?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php if ($provider->totalCount > 0) { ?>
                <div class="courses-container mt-2">
                    <?php $courses = $provider->getModels(); ?>
                    <?php foreach ($courses as $t) {
                        /* @var $t \common\models\Courses */
                        ?>
                        <div assign_item="course" assign_id="<?=$t->id?>" class="assign-item course-item list-item mt-3 white-block">
                            <div class="row">
                                <div class="col-auto">
                                    <a href="<?=\app\helpers\OrganizationUrl::to(["/courses/view", "id" => $t->id])?>" class="icon-circle img-icon icon-circle-lg bg-warning"><i class="icon-1"></i></a>
                                </div>
                                <div class="col align-self-center pl-3">
                                    <h4>
                                        <a href="<?=\app\helpers\OrganizationUrl::to(["/courses/view", "id"=>$t->id])?>"><?=$t->name?></a>
                                    </h4>
                                </div>
                                <div class="col col-auto ml-auto align-self-center">
                                    <div class="row">
                                        <?php if ($t->state == \app\models\Courses::CREATED) { ?>
                                            <div class="col col-auto">
                                                <i title="<?=Yii::t("main","Курс не опубликован. Слушатели не получат доступ к курсу, пока он не будет опубликован")?>" class="text-danger fa fa-info-circle"></i>
                                            </div>
                                        <?php } ?>
                                        <div class="col col-auto">
                                            <i class="text-muted fa fa-tasks"></i> <?=intval(count($t->lessons))?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mt-2 mb-2"><?= nl2br(\Yii::$app->request->get("from") ? $t->shortDescription : $t->description) ?></p>
                            <div class="row">
                                <div class="col col-auto text-muted">
                                    <i class="fa fa-tags"></i> <?= $t->tagsString?>
                                </div>
                                <div class="col col-auto text-muted">
                                    <i class="fa fa-eye"></i> <?=$t->viewsCount?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?= \app\widgets\EPager\EPager::widget([
                        'pagination' => $provider->pagination,
                    ]) ?>

                </div>
            <?php } else { ?>
                <div class="white-block mt-2">
                    <div class="mb-0 alert alert-warning"><h5><?= Yii::t("main", "Курсов не найдено") ?></h5></div>
                </div>
            <?php } ?>

        </div>
        <div class="col-3">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Фильтры")?></h3>
                </div>
            </div>

            <div class="white-block mt-3 filter-panel">

                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/courses/index'
                ])?>

            </div>

        </div>
    </div>

</div>
