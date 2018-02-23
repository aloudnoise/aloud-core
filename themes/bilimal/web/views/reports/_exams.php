<?php

$lists = $filter->lists;

?>

<?=$this->render("@app/views/reports/additional_filter", [
    "filter" => $filter,
    "lists" => $lists,
    "teacher" => in_array("teacher", $filter->items),
    "theme" => in_array("theme", $filter->items),
    "group" => in_array("group", $filter->items),
    "result" => in_array("result", $filter->items)
])?>

<div class="white-block mt-1">
    <?php
    $data = $filter->data;
    if ($data) {
        ?>

        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="row d-print-none">
                    <div class="col col-auto ml-auto"><a target=".export-content" export-size="11" export-file-name="<?=Yii::t("main","Ведомость")?>" export-type="word" class="btn-sm text-white pointer btn btn-primary btn-export"><?=Yii::t("main","Экспорт в Word")?></a></div>
                </div>
                <page size="A4" class="mt-1">
                    <div class="export-content">
                        <p class="text-center"><?=\bilimal\web\models\Organizations::getCurrentOrganization()->name?></p>
                        <p class="text-center">наименование организации технического и профессионального,послесреднего образования</p>
                        <p class="mt-4"></p>
                        <p></p>
                        <p></p>
                        <p class="text-center"><strong>ЭКЗАМЕНАЦИОННАЯ ВЕДОМОСТЬ</strong></p>
                        <p class="text-center"><strong>(для промежуточной аттестации обучающихся)</strong></p>
                        <p class="mt-4"></p>
                        <p></p>
                        <p class=""><?=str_repeat("&nbsp;", 20)?>по предмету <u><?=str_repeat("&nbsp;", 36)?></u>«<u><?=str_repeat("&nbsp;", 13)?></u>» курса <u><?=str_repeat("&nbsp;", 30)?></u> группы<u><?=str_repeat("&nbsp;", 36)?></u></p>
                        <p class=""><?=str_repeat("&nbsp;", 20)?>специальность <u><?=str_repeat("&nbsp;", 140)?></u></p>
                        <p class=""><?=str_repeat("&nbsp;", 20)?>экзаменатор <u><?=str_repeat("&nbsp;", 50 - ($filter->event ? floor(mb_strlen($filter->event->owner->fio, "UTF-8")*0.86) : 0))?><?=$filter->event ? $filter->event->owner->fio : ""?><?=str_repeat("&nbsp;", 85 - ($filter->event ? floor(mb_strlen($filter->event->owner->fio, "UTF-8")*0.85) : 0))?></u></p>
                        <p class="text-center">(фамилия, имя, отчество)</p>
                        <p class="mt-4"></p>
                        <p></p>
                        <table class="mt-2 table table-bordered table-sm border-0">
                            <tr>
                                <td tid="1" rowspan="2"><p class="text-center">№</p><p class="text-center">п/п</p></td>
                                <td tid="2" rowspan="2"><p class="text-center">Номер экзаминационного билета</p></td>
                                <td tid="3" rowspan="2"><p class="text-center">Фамилия, имя, отчество экзаменующегося</p></td>
                                <td  colspan="3"><p class="text-center">Оценки по экзаменам</p></td>
                                <td tid="4" rowspan="2"><p class="text-center">Подпись экзаменатора</p></td>
                            </tr>
                            <tr>
                                <td rid="[1,2,3]"><p class="text-center">Письменно</p></td>
                                <td><p class="text-center">Устно</p></td>
                                <td><p class="text-center">Общая</p></td>
                            </tr>
                            <tr>
                                <td><p class="text-center"><strong>1</strong></p></td>
                                <td><p class="text-center"><strong>2</strong></p></td>
                                <td><p class="text-center"><strong>3</strong></p></td>
                                <td><p class="text-center"><strong>4</strong></p></td>
                                <td><p class="text-center"><strong>5</strong></p></td>
                                <td><p class="text-center"><strong>6</strong></p></td>
                                <td><p class="text-center"><strong>7</strong></p></td>
                            </tr>
                            <?php
                                $n = 1;
                            ?>
                            <?php foreach ($data as $tr) { ?>
                                <tr>
                                    <td><p class="text-center"><?=$n?></p></td>
                                    <td style="width:15%;"><p></p></td>
                                    <td style="width:30%;"><p><?=$tr->user->fio?></td>
                                    <td><p class="text-center"><?=$tr->translated_result ?: $tr->result?></p></td>
                                    <td><p class="text-center"></p></td>
                                    <td><p class="text-center"></p></td>
                                    <td><p class="text-center"></p></td>
                                </tr>
                            <?php $n++; } ?>
                        </table>
                        <p class="mt-4"></p>
                        <p></p>
                        <p><?=str_repeat("&nbsp;", 20)?>«<u><?=str_repeat("&nbsp;", 25)?></u>»<u><?=str_repeat("&nbsp;", 10)?></u>20<u><?=str_repeat("&nbsp;", 15)?></u>г</p>
                        <p><?=str_repeat("&nbsp;", 20)?>Время проведения экзаменов:</p>
                        <p><?=str_repeat("&nbsp;", 20)?>Письменного<u><?=str_repeat("&nbsp;",35)?></u>начало<u><?=str_repeat("&nbsp;",40)?></u>окончание<u><?=str_repeat("&nbsp;",40)?></u></p>
                        <p><?=str_repeat("&nbsp;", 20)?>Устного<u><?=str_repeat("&nbsp;",44)?></u>начало<u><?=str_repeat("&nbsp;",40)?></u>окончание<u><?=str_repeat("&nbsp;",40)?></u></p>
                        <p><?=str_repeat("&nbsp;", 20)?>Всего часов на проведение экзаменов<u><?=str_repeat("&nbsp;",30)?></u>час<u><?=str_repeat("&nbsp;",30)?></u>мин<u><?=str_repeat("&nbsp;",31)?></u></p>
                        <p><?=str_repeat("&nbsp;", 20)?>Подпись экзаменатора<u><?=str_repeat("&nbsp;",129)?></u></p>

                    </div>
                </page>
            </div>
        </div>

    <?php } else { ?>
        <div class="alert alert-danger mb-0"><?=Yii::t("main","Тестирований за данный период не проводилось")?></div>
    <?php } ?>
</div>
