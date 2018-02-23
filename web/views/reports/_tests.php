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

<div>
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
                    <th><?=Yii::t("main","Тест")?></th>
                    <th><?=Yii::t("main","Слушатель")?></th>
                    <th><?=Yii::t("main","Дата")?></th>
                    <th><?=Yii::t("main","Время прохождения")?></th>
                    <th><?=Yii::t("main","Правильных ответов")?></th>
                    <th colspan="<?=\Yii::$app->request->get("type") != "extra" ? 1 : 2?>"><?=Yii::t("main","Результат")?></th>
                </tr>

                <?php
                $n = 1;


                $themes = $lists['themes'];

                $summary = [
                    'count' => 0,
                    'ball' => 0,
                ];

                foreach ($data as $tr) { ?>

                    <?php

                        $summary['count']++;
                        $summary['ball'] += intval($tr->result);
                    ?>

                    <?php if (\Yii::$app->request->get("type") != "extra") { ?>
                        <tr class="<?=!$tr->isActive ? 'table-danger' : ''?>">
                            <td><?=$n?></td>
                            <td><?=$tr->getFromModel()->name?></td>
                            <td><?=$tr->test->name?></td>
                            <td><?=$tr->user->fio?></td>
                            <td><?=\app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $tr->ts,
                                    "formatType" => 2
                                ])?>
                            </td>
                            <td><?=$tr->processTime?></td>
                            <td><?=$tr->correct_answers." из ".count($tr->infoJson['questions'])?></td>
                            <td class="text-nowrap">
                                <div class="row">
                                    <div class="col-auto">
                                        <b class="text-<?=$tr->resultTextColor?>"><?=$tr->translatedResultText?></b> <a class="no-export" target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/tests/process/result", 'id' => $tr->id])?>"><i class="fa fa-eye"></i></a>
                                    </div>
                                <?php if ($filter->event AND $tr->isActive) { ?>
                                    <div class="no-export col-auto ml-auto">
                                        <a target="modal" class="text-danger" title="<?=Yii::t("main","Сбросить результат")?>" href="<?=\app\helpers\OrganizationUrl::to(["/tests/process/discard", 'id' => $tr->id])?>"><i class="fa fa-times"></i></a>
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
                    <?php } else {

                        $rowspan = count($tr->infoJson['by_themes'])+2;

                        ?>
                        <tr>
                            <td style="vertical-align: middle; border-bottom:solid 1px #000;" rowspan="<?=$rowspan?>"><?=$n?></td>
                            <td style="vertical-align: middle; border-bottom:solid 1px #000;" rowspan="<?=$rowspan?>"><?=$tr->test->name?></td>
                            <td style="vertical-align: middle; border-bottom:solid 1px #000;" rowspan="<?=$rowspan?>"><?=$tr->user->fio?></td>
                            <td style="vertical-align: middle; border-bottom:solid 1px #000;" rowspan="<?=$rowspan?>"><?=\app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $tr->ts,
                                    "formatType" => 2
                                ])?>
                            </td>
                            <td><?=floor((strtotime($tr->finished) - strtotime($tr->ts))/60)."м. ".((strtotime($tr->finished) - strtotime($tr->ts)) % 60)."с. "?></td>
                            <td style="vertical-align: middle; border-bottom:solid 1px #000;" rowspan="<?=$rowspan?>"><?=$tr->correct_answers." из ".$tr->test->qcount?></td>
                        </tr>
                        <?php
                        if (!empty($tr->infoJson['by_themes'])) {
                            foreach ($tr->infoJson['by_themes'] as $theme => $result) { ?>
                                <tr>
                                    <td><?= $themes[$theme]->name ?></td>
                                    <td>
                                        <span class="<?= \app\models\results\TestResults::textColor($result) ?>"><?= $result ?>
                                            %</span></td>
                                </tr>
                            <?php }
                        }?>

                        <tr>
                            <td style=" border-bottom:solid 1px #000;"><b><?=Yii::t("main","Всего")?></b></td>
                            <td style=" border-bottom:solid 1px #000;"><b class="text-<?=$tr->resultTextColor?>"><?=$tr->translatedResultText?></b></td>
                        </tr>

                    <?php } ?>
                <?php
                    $n++;
                } ?>

                <tr class="table-secondary">
                    <th colspan="8">
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
                                    'avg' => "<span class='text-".\common\models\results\TestResults::textColor(ceil($summary['ball']/$summary['count']))."'>".ceil($summary['ball']/$summary['count'])."%</span>"
                                ])?>
                            </div>
                        </div>
                    </th>
                </tr>

            </table>
        </small>
    <?php } else { ?>
        <div class="alert alert-danger mb-0"><?=Yii::t("main","Тестирований за данный период не проводилось")?></div>
    <?php } ?>
</div>
