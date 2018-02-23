<?php

$lists = $filter->lists;

?>

<?=$this->render("additional_filter", [
    "filter" => $filter,
    "lists" => $lists,
    "teacher" => in_array("teacher", $filter->items),
    "theme" => in_array("theme", $filter->items),
    "group" => in_array("group", $filter->items),
    "result" => false
])?>


<div class="">
    <?php
    $data = $filter->data;
    if ($data) {
        ?>

        <div class="mb-2 row d-print-none">
            <div class="col col-auto ml-auto"><a target=".export-content" export-type="excel" export-file-name="<?=Yii::t("main","Результаты тестирования")?>" class="btn-sm text-white pointer btn btn-primary btn-export"><?=Yii::t("main","Экспорт в Excel")?></a></div>
        </div>

        <small class="export-content">
            <table class="table table-bordered table-sm mb-0">
                <tr>
                    <th><?=Yii::t("main","№")?></th>
                    <th><?=Yii::t("main","Источник")?></th>
                    <th><?=Yii::t("main","Материал")?></th>
                    <th><?=Yii::t("main","Слушатель")?></th>
                    <th><?=Yii::t("main","Дата")?></th>
                    <th><?=Yii::t("main","Время просмотра")?></th>
                </tr>

                <?php
                $n = 1;

                $summary = [
                    'count' => 0,
                ];

                $pupils = [];

                $current_material = null;
                $time = 0;
                foreach ($data as $tr) { ?>
                    <?php
                    if (!in_array($tr->user_id, $pupils)) {
                        $summary['count']++;
                        $pupils[] = $tr->user_id;
                    }

                    if ($current_material == null) {
                        $current_material = $tr;
                    }

                    if ($current_material->material_id != $tr->material_id) {

                        ?>
                        <tr>
                            <td><?= $n ?></td>
                            <td><?=$current_material->getFromModel()->name?></td>
                            <td><?= $current_material->material->name ?></td>
                            <td><?= $current_material->user->fio ?></td>
                            <td><?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $current_material->ts,
                                    "formatType" => 2
                                ]) ?>
                            </td>
                            <td><?= floor($time/60)."м. ".($time % 60)."с. " ?></td>
                        </tr>
                        <?php
                        $n++;
                        $current_material = $tr;
                        $time = 0;
                    } else {
                        $time += $tr->processTimeSeconds;
                    }
                } ?>

                <tr>
                    <td><?= $n ?></td>
                    <td><?=$current_material->getFromModel()->name?></td>
                    <td><?= $current_material->material->name ?></td>
                    <td><?= $current_material->user->fio ?></td>
                    <td><?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => $current_material->ts,
                            "formatType" => 2
                        ]) ?>
                    </td>
                    <td><?= floor($time/60)."м. ".($time % 60)."с. " ?></td>
                </tr>

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
                        </div>
                    </th>
                </tr>

            </table>
        </small>
    <?php } else { ?>
        <div class="alert alert-danger mb-0"><?=Yii::t("main","Материалов за данный период не просмотрено")?></div>
    <?php } ?>
</div>
