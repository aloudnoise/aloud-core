<?php
    $this->setTitle(Yii::t("main","Задания для уроков"), false);
?>
<div class="action-content">

    <div class="row big-gutter">
        <div class="col-9">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Задания")?></h3>
                </div>
                <?php if (\Yii::$app->user->can("base_teacher") AND !\Yii::$app->request->get("assign")) { ?>
                    <div class="col col-auto ml-auto">
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/tasks/add"])?>" class="btn btn-primary"><?=Yii::t("main","Добавить задание")?></a>
                    </div>
                <?php } ?>

            </div>

            <div class="mt-3 filter-panel white-block">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень заданий")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового задания, нажмите кнопку {add_link}, и заполните необходимые поля: название задания, содержание и прочее.", [
                                'add_link' => "<a class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/tasks/add"])."'>".Yii::t("main","Добавить задание")."</a>"
                            ])?></p>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Поиск заданий")?>" type="text" value="<?=$filter->search?>" class="find-input form-control" name="search" />
                    </div>
                    <div class="col col-auto">
                        <a  data-role="filter" data-action="submit" class="pointer btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <?php if (\Yii::$app->user->can("base_teacher")) { ?>
                        <div class="col">
                            <div class="btn-group">
                                <?php
                                $get = \Yii::$app->request->get();
                                $get['filter']['pr'] = 1;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 1 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/tasks/index"], $get))?>"><?=Yii::t("main","Мои задания")?></a>
                                <?php
                                $get['filter']['pr'] = 2;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 2 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/tasks/index"],$get))?>"><?=Yii::t("main","Все задания")?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php if ($provider->totalCount > 0) { ?>
                    <div class="courses-container mt-3">
                        <?php $header = new \app\models\Tasks(); ?>
                        <table class="table mb-0">
                            <tr>
                                <th colspan="2"><?=$header->getAttributeLabel("name")?></th>
                                <th><?=$header->getAttributeLabel("content")?></th>
                                <th><?=$header->getAttributeLabel("time")?></th>
                            </tr>
                            <?php
                            $models = $provider->getModels();
                            foreach ($models as $m) {
                                echo \app\widgets\ETask\ETask::widget([
                                    "backbone" => false,
                                    "type" => "row",
                                    "model" => $m,
                                ]);
                            }
                            ?>
                        </table>

                        <?= \app\widgets\EPager\EPager::widget([
                            'pagination' => $provider->pagination,
                        ]) ?>

                    </div>
                <?php } else { ?>
                    <div class="mt-3">
                        <div class="mb-0 alert alert-warning"><h5><?= Yii::t("main", "Заданий не найдено") ?></h5></div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-3">

            <div class="row">
                <div class="col">
                    <h3 class="mb-0 font-weight-4"><?=Yii::t("main","Фильтры")?></h3>
                </div>
            </div>

            <div class="white-block mt-3 filter-panel">

                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/tasks/index'
                ])?>

            </div>

        </div>
    </div>

</div>