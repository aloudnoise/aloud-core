<?php

$lists = $filter->lists;

?>

<?=$this->render("additional_filter", [
    "filter" => $filter,
    "lists" => $lists,
    "teacher" => in_array("teacher", $filter->items),
    "theme" => in_array("theme", $filter->items),
    "group" => in_array("group", $filter->items),
    "result" => in_array("result", $filter->items)
])?>


<div class="">
    <?php
    $data = $filter->data;
    if ($data) {
        ?>

        <div class="mb-2 row d-print-none">
            <div class="col col-auto ml-auto"><a target=".export-content" export-type="excel" export-file-name="<?=Yii::t("main","Результаты заданий")?>" class="btn-sm text-white pointer btn btn-primary btn-export"><?=Yii::t("main","Экспорт в Excel")?></a></div>
        </div>

        <small class="export-content">
            <table class="table table-bordered table-sm mb-0">
                <tr>
                    <th><?=Yii::t("main","№")?></th>
                    <th><?=Yii::t("main","Источник")?></th>
                    <th><?=Yii::t("main","Задание")?></th>
                    <th><?=Yii::t("main","Слушатель")?></th>
                    <th><?=Yii::t("main","Дата")?></th>
                    <th><?=Yii::t("main","Время прохождения")?></th>
                    <th><?=Yii::t("main","Результат")?></th>
                </tr>

                <?php
                $n = 1;

                $summary = [
                    'count' => 0,
                    'ball' => 0,
                ];

                foreach ($data as $tr) { ?>
                    <?php
                    $summary['count']++;
                    $summary['ball'] += intval($tr->result);
                    ?>
                    <tr class="<?=!$tr->isActive ? 'table-danger' : ''?>">
                        <td><?=$n?></td>
                        <td><?=$tr->getFromModel()->name?></td>
                        <td><?=$tr->task->name?></td>
                        <td><?=$tr->user->fio?></td>
                        <td><?=\app\widgets\EDisplayDate\EDisplayDate::widget([
                                "time" => $tr->ts,
                                "formatType" => 2
                            ])?>
                        </td>
                        <td><?=$tr->processTime?></td>
                        <td class="text-nowrap">
                            <div class="row">
                                <div class="col-auto">
                                    <b class="text-<?=$tr->resultTextColor?>"><?=$tr->translatedResultText?></b> <a class="no-export" target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/tasks/finish", 'id' => $tr->id])?>"><i class="fa fa-eye"></i></a>
                                </div>
                                <?php if ($filter->event AND $tr->isActive) { ?>
                                <div class="no-export col-auto ml-auto">
                                    <a target="modal" class="text-danger" title="<?=Yii::t("main","Сбросить результат")?>" href="<?=\app\helpers\OrganizationUrl::to(["/tasks/discard", 'id' => $tr->id])?>"><i class="fa fa-times"></i></a>
                                </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php if (!$tr->isActive) { ?>
                        <tr class="table-danger">
                            <td colspan="8"><?=Yii::t("main","Причина сброса: {reason}", [
                                    'reason' => "<strong>".$tr->status_note."</strong>"
                                ])?></td>
                        </tr>
                    <?php } ?>
                    <?php
                    $n++;
                } ?>

                <tr class="table-secondary">
                    <th colspan="7">
                        <div class="row">
                            <div class="col-auto">
                                <?=Yii::t("main","Всего")?>
                            </div>
                            <div class="col-auto mr-3 ml-auto">
                                <?=Yii::t("main","Слушателей: {count}", [
                                    'count' => "<span class='text-primary'>".$summary['count']."</span>"
                                ])?>
                            </div>
                            <div class="col-auto">
                                <?=Yii::t("main","Средний балл: {avg}", [
                                    'avg' => "<span class='text-".\common\models\results\TaskResults::textColor(ceil($summary['ball']/$summary['count']))."'>".ceil($summary['ball']/$summary['count'])."%</span>"
                                ])?>
                            </div>
                        </div>
                    </th>
                </tr>

            </table>
        </small>
    <?php } else { ?>
        <div class="alert alert-danger mb-0"><?=Yii::t("main","Решенных заданий за данный период нет")?></div>
    <?php } ?>
</div>
