<?php
$this->setTitle(Yii::t("main","Отчеты"));
\app\bundles\tools\ToolsBundle::registerTool($this, "stickytableheaders");
?>
<div class="action-content">

    <div class="mb-3">
        <h3><?=Yii::t("main","Отчеты")?></h3>
    </div>

    <div class="white-block filter-panel d-print-none">
        <div class="row align-content-center">
            <div class="col">
                <div class="row">
                    <div class="col-auto align-self-center">
                        <select class="form-control form-control type-select">
                            <?php foreach ($filter->reports as $report) { ?>
                                <?php if (!$report['role'] OR \Yii::$app->user->can($report['role'])) { ?>
                                    <option <?=$filter->type == $report['name'] ? "selected" : "" ?> value="<?=\app\helpers\OrganizationUrl::to(array_merge([\Yii::$app->controller->route], \Yii::$app->request->get(), ['filter' => array_merge($filter->attributes, ['type'=>$report['name']])]))?>"><?=$report['label']?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <?php if (in_array("period", $filter->items)) { ?>
                <div class="col-auto ml-auto">
                    <div class="input-group">
                        <input  data-role="filter" data-action="input" name="start" type="text" value="<?=$filter->start?>" class="from-input form-control" />
                        <input data-role="filter" data-action="input" name="end" type="text" value="<?=$filter->end?>" class="to-input form-control" />
                    </div>
                </div>
            <?php } ?>

            <div class="col-auto ml-auto">
                <a data-role="filter" data-action="submit" class="pointer find-button btn btn-primary" tabindex="1"><?=Yii::t("main","Найти")?></a>
            </div>

        </div>

        <hr />

        <div class="reports-container">

            <?php if ($filter->type) { ?>
                <?=$this->render('_' .$filter->type, array(
                    'filter' => $filter,
                ))?>
            <?php } else { ?>
                <div class="">
                    <div class="alert alert-info mb-0">
                        <?=Yii::t("main","Выберите детали отчета")?>
                    </div>
                </div>
            <?php } ?>

        </div>

    </div>

</div>