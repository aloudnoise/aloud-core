<?php
(Yii::$app->assetManager->getBundle("backbone"))::registerWidget($this, "EDisplayDate");
$this->addTitle(Yii::t("main","Список групп"));

?>

<div class="action-content">

    <div class="row">

        <div class="col">

            <?php if (\Yii::$app->user->can("specialist")) { ?>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-light btn-lg font-weight-6 text-muted" href="<?=\app\helpers\OrganizationUrl::to(["/hr/users/index", 'filter' => ['type' => \app\models\filters\UsersFilter::TYPE_PUPILS]])?>"><?=Yii::t("main","Слушатели")?></a>
                        <a class="btn btn-light btn-lg font-weight-6 text-muted" href="<?=\app\helpers\OrganizationUrl::to(["/hr/users/index", 'filter' => ['type' => \app\models\filters\UsersFilter::TYPE_STAFF]])?>"><?=Yii::t("main","Сотрудники")?></a>
                        <a class="btn btn-light btn-lg font-weight-6 text-warning" href="<?=\app\helpers\OrganizationUrl::to(["/hr/groups/index"])?>"><?=Yii::t("main","Группы")?></a>
                    </div>
                </div>
            <?php } ?>

            <div class="mt-3 white-block filter-panel">
                <div class="row">
                    <?php if (!Yii::$app->request->get("assign") AND \common\models\Organizations::getCurrentOrganizationId() !== 0) { ?>
                        <div class="col col-auto">
                            <a target="modal"  href="<?=\app\helpers\OrganizationUrl::to(["/hr/groups/add"])?>" class="btn btn-success">
                                <?=Yii::t("main","Добавить группу")?></a>
                        </div>
                    <?php } ?>
                    <div class="col-12 mt-3">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень групп")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания новой группы, нажмите кнопку {add_link}, и заполните необходимые поля: название группы и другие.", [
                                'add_link' => "<a target=\"modal\" class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/hr/groups/add"])."'>".Yii::t("main","Добавить пользователя")."</a>"
                            ])?></p>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?= Yii::t("main", "Поиск") ?>" type="text" value="<?= $filter->search ?>" class="form-control" name="search"/>
                    </div>
                    <div class="col col-auto">
                        <a href="#" data-role="filter" data-action="submit" class="btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <?php if (\Yii::$app->user->can("specialist")) { ?>
                        <div class="col">
                            <div class="btn-group">
                                <?php
                                $get = \Yii::$app->request->get();
                                $get['filter']['pr'] = 1;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 1 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/hr/groups/index"], $get))?>"><?=Yii::t("main","Мои группы")?></a>
                                <?php
                                $get['filter']['pr'] = 2;
                                ?>
                                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 2 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/hr/groups/index"],$get))?>"><?=Yii::t("main","Все группы")?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <table class="groups table table-sm table-bordered table-hover mb-0 mt-3">

                    <thead>
                    <tr>
                        <th><?=Yii::t("main","Название")?></th>
                        <th><?=Yii::t("main","Количество участников")?></th>
                        <th><?=Yii::t("main","Дата создания")?></th>
                        <th><?=Yii::t("main","Статус")?></th>
                    </tr>
                    </thead>

                    <tbody style="border-top:0;" class="groups-body ">
                    <?php $groups = $provider->getModels() ?>
                    <?php if ($groups) { ?>
                        <?php foreach ($groups as $group) { ?>
                            <tr>
                                <td style="vertical-align: middle;"><a href="<?=\app\helpers\OrganizationUrl::to(['/hr/groups/view', 'id' => $group->id])?>"><?=$group->name?></a></td>
                                <td style="vertical-align: middle;"><?=count($group->users)?></td>
                                <td style="vertical-align: middle;"><?=$group->getByFormat('ts', 'd.m.Y')?></td>
                                <?php if ($filter->pr == 1 OR \Yii::$app->user->can("specialist")) { ?>
                                    <td style="vertical-align: middle;">
                                        <div class="row">
                                            <div class="col-auto ml-auto text-nowrap">
                                                <a target="modal" class="text-primary" href="<?=\app\helpers\OrganizationUrl::to(['/hr/groups/add', 'id' => $group->id])?>"><i class="fa fa-pencil"></i></a>
                                                <a target="modal" class="text-danger" confirm="<?=Yii::t("main", "Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(['/hr/groups/delete', 'id' => $group->id])?>"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>

                </table>

                <?= \app\widgets\EPager\EPager::widget([
                    'pagination' => $provider->pagination,
                ]) ?>
            </div>

        </div>

    </div>

</div>
