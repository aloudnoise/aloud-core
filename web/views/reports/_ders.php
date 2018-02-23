<?php

$lists = $filter->lists;

?>

<div class="">
    <div class="row">
        <div class="col-6">
            <div class="form-group mb-0">
                <?=\app\helpers\Html::dropDownList("custom[der_activity_id]", $filter->custom['der_activity_id'], \app\helpers\ArrayHelper::map($lists['ders'], 'activity_id', 'name'), [
                    'prompt' => Yii::t("main",'По ЦОР'),
                    'class' => 'form-control',
                    'data-role' => 'filter',
                    'data-action' => 'input'
                ])?>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="">
    <small class="der-results">
        <table class="table table-bordered der-results-table">
            <tr class="header">
                <th><?=Yii::t("main","Слушатель")?></th>
                <th><?=Yii::t("main","Действие")?></th>
                <th><?=Yii::t("main","Обьект")?></th>
                <th><?=Yii::t("main","Время прохождения")?></th>
                <th><?=Yii::t("main","Результат")?></th>
                <th><?=Yii::t("main","Дата и время")?></th>
            </tr>

            <?php
            $statements = $filter->getData();
            if ($statements) {
                $data = $statements->content->getStatements();
                $data = array_reverse($data);
                foreach ($data as $statement) {
                    /* @var $statement \TinCan\Statement */

                    $actor = $statement->getActor();

                    ?>
                    <tr>
                        <td><?=$actor->getName()?></td>
                        <td><?=$statement->getVerb()->getDisplay()->asVersion()['en-US']?></td>
                        <td><?=$statement->getTarget()->getDefinition()->getName()->asVersion()['ru-RU'] ? $statement->getTarget()->getDefinition()->getName()->asVersion()['ru-RU'] : $statement->getTarget()->getDefinition()->getName()->asVersion()['en-Us']?></td>

                        <?php
                            $result = $statement->getResult();
                        /* @var $result \TinCan\Result */

                        $r = explode(".", $result->getDuration());
                        if ($r[1]) {
                            $r[1] = intval($r[1]);
                        }

                        $duration = str_replace(".".$r[1], "", $result->getDuration());
                        $duration = str_replace(".".($r[1]<10 ? '0'.$r[1] : $r[1]), "", $duration);
                        if (!empty($duration)) {
                            try {
                                $interval = new DateInterval($duration);
                            } catch (Exception $e) {

                            }
                        }

                        ?>

                        <td><?=$interval ? $interval->format('%i м., %S с.') : $result->getDuration()?></td>
                        <td><?=$result->getResponse()?></td>
                        <td><?=date('d.m.Y H:i:s',strtotime($statement->getTimestamp()))?></td>
                    </tr>
                    <?php
                }
                ?>

            <?php } ?>

        </table>

    </small>
</div>