<?php
$this->setTitle(Yii::t("main","Вопросы"));
?>
<div class="action-content">

    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Вопросы")?></h3>
                </div>
                <div class="col col-auto ml-auto">
                    <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(['/dics/add', 'dic' => 'DicQuestionThemes'])?>" class="btn btn-primary"><?=Yii::t("main","Добавить тему")?></a>
                </div>
            </div>

            <div class="mt-3 white-block filter-panel">
                <div class="row">
                    <div class="col-12 mt-3">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень вопросов по темам")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания новой темы, нажмите кнопку {add_link}, и заполните название темы. К каждой теме вы можете добавить вопросы, либо импортировать список вопросов", [
                                'add_link' => "<a target=\"modal\" class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/dics/add", 'dic' => 'DicQuestionThemes'])."'>".Yii::t("main","Добавить тему")."</a>"
                            ])?></p>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Поиск вопросов")?>" type="text" value="<?=$filter->search?>" class="find-input form-control" name="search" />
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
                                <a class="font-weight-6 btn btn-light nav-link text-muted" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/tests/base/index"], $get))?>"><?=Yii::t("main","Мои тесты")?></a>
                                <?php
                                $get['filter']['pr'] = 2;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link text-muted" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/tests/base/index"],$get))?>"><?=Yii::t("main","Все тесты")?></a>
                                <?php if (!Yii::$app->request->get("from")) { ?>
                                    <a class="font-weight-6 btn btn-light nav-link text-warning" href="<?=app\helpers\OrganizationUrl::to(["/tests/questions/index"])?>"><?=Yii::t("main","Банк вопросов")?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php if ($provider->totalCount > 0) { ?>
                <div class="themes-container">
                    <?php $themes = $provider->getModels(); ?>
                    <?php foreach ($themes as $theme) { ?>
                        <div assign_item="theme" assign_id="<?=$theme->id?>" class="assign-item white-block mt-3 theme-item position-relative">
                            <?php if (!\Yii::$app->request->get("from")) { ?>
                                <div class="actions-panel" style="top:15px;">
                                    <div class="row justify-content-end">
                                        <div class="col col-auto align-self-center">
                                            <div class="btn-group">
                                                <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(['/dics/add', 'id' => $theme->id, 'dic' => 'DicQuestionThemes'])?>" class="btn-sm btn btn-info"><?=Yii::t("main","Изменить")?></a>
                                                <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'theme_id' => $theme->id])?>" class="btn-sm btn btn-primary"><?=Yii::t("main","Конструктор")?></a>
                                                <?php if (Yii::$app->user->can("admin")) { ?>
                                                    <a  noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/dics/delete', 'id' => $theme->id, 'return' => \Yii::$app->request->url])?>" confirm="<?=Yii::t("main","Вы уверены? Тема будет полностью удалена")?>" class="btn-sm btn btn-danger"><?=Yii::t("main","Удалить")?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col">
                                    <?php if (!\Yii::$app->request->get("from")) { ?>
                                        <h5 class="text-muted"><?= $theme->nameByLang ?></h5>
                                    <?php } else { ?>
                                        <a href="#"><h5 class="text-muted"><?= $theme->nameByLang ?></h5></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col col-auto">
                                    <?=Yii::t("main","Вопросов: ")?> <strong class="text-muted"><?=count($theme->questions)?></strong>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?= \app\widgets\EPager\EPager::widget([
                        'pagination' => $provider->pagination,
                    ]) ?>

                </div>

            <?php } else if ($filter->search) { ?>

                <div class="white-block mt-2">
                    <div class="mb-0 alert alert-warning"><h5>
                            <?= Yii::t("main", "Тем не найдено");?>
                        </h5></div>
                </div>

            <?php } else { ?>

                <div class="white-block mt-2">
                    <div class="mb-0 alert alert-warning"><h5>
                            <?= Yii::t("main", "Еще не добавлено ни одной темы");?>
                        </h5></div>
                </div>

            <?php } ?>

        </div>


    </div>


</div>