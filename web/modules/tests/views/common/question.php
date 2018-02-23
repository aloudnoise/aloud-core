<?php
$literas = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
?>
<div class="question list-item">
    <div class="row">
        <?php if ($n) { ?>
        <div class="col-auto">
            <h3 class="text-primary"><?= $n ?></h3>
        </div>
        <?php } ?>
        <div class="col">
            <?php if ($link) { ?>
                <h5><a href="<?=$link?>"><?= $question->nameByLang ?></a></h5>
            <?php } else { ?>
                <h5><?= $question->nameByLang ?></h5>
            <?php } ?>
        </div>
        <?php if ($question->is_random) { ?>
            <div class="col-auto ml-auto text-muted">
                <?= Yii::t("main","Варианты в случайном порядке") ?>
            </div>
        <?php } ?>
    </div>
    <div class="answers-list mt-3">
        <?php
        $an = 0;
        if (!empty($question->answers)) {
            foreach ($question->answers as $answer) {
                ?>
                <div class="row mb-2">
                    <div class="col-auto"><h5 class="text-info"><?= $literas[$an] ?></h5></div>
                    <div class="col">
                        <?= $answer->nameByLang ?>
                    </div>
                    <div class="col-auto">
                        <?= $answer->is_correct == 1 ? "<span title=\"" . $literas[$an] . " " . Yii::t("main", "Правильный ответ") . "\" class=\"badge badge-success\"><i class=\"fa fa-check\"></i></span>" : "" ?>
                    </div>
                </div>
                <?php
                $an++;
            }
        }
        ?>
    </div>
</div>