<?php
(Yii::$app->assetManager->getBundle("backbone"))::registerWidget($this, "EDisplayDate");
$this->addTitle(Yii::t("main","Список организаций"));

?>

<div class="action-content">

    <div class="row">

        <div class="col">

            <div class="mt-1 white-block filter-panel border-warning">
                <div class="row">
                    <div class="col">
                        <h5><?=Yii::t("main","Организации")?></h5>
                    </div>
                </div>
            </div>

            <div class="white-block mt-2 mb-0">
                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?= Yii::t("main", "Поиск") ?>" type="text" value="<?= $filter->search ?>" class="form-control" name="search"/>
                    </div>
                    <div class="col col-auto">
                        <a href="#" data-role="filter" data-action="submit" class="btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                    </div>
                </div>
            </div>

            <div class="white-block mt-1">
                <table class="groups table table-sm table-bordered table-hover mb-0">

                    <thead>
                    <tr>
                        <th><?=Yii::t("main","Название")?></th>
                        <th><?=Yii::t("main","Тип")?></th>
                        <th><?=Yii::t("main","Дата создания")?></th>
                    </tr>
                    </thead>

                    <?php $organizations = $provider->getModels() ?>
                    <?php if ($organizations) { ?>
                        <?php foreach ($organizations as $organization) { ?>
                            <tr class="assign-item" assign_item="organization" assign_id="<?=$organization->id?>">
                                <td style="vertical-align: middle;"><a href="<?=\app\helpers\OrganizationUrl::to(['/organizations/view', 'id' => $organization->id])?>"><?=$organization->name?></a></td>
                                <td><?=$organization->typeCaption?></td>
                                <td style="vertical-align: middle;"><?=$organization->getByFormat('ts', 'd.m.Y')?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>

                </table>

                <?= \app\widgets\EPager\EPager::widget([
                    'pagination' => $provider->pagination,
                ]) ?>
            </div>

        </div>

    </div>

</div>
