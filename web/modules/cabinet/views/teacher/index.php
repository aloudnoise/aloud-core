<?php $this->setTitle(Yii::t("main","Личный кабинет учителя")) ?>
<div class="action-content">

    <div class="asm-wrapper">
        <div class="asm-block">
            <div class="cont">
                <div class="title">
                    <?=Yii::t("main","Ближайшие мероприятия")?>
                </div>
                <div class="labels">
                    Текущие
                </div>
                <?php
                if (!empty($last_events)) {
                    foreach ($last_events as $n) { ?>
                <div class="item">
                    <div class="icon">
                        <div class="circle">
                            <div class="va">
                                <i class="fa fa-file-text-o vam"></i>
                            </div>
                        </div>
                    </div>
                    <div class="info">
                        <div class="title">
                            <a href="<?= \app\helpers\OrganizationUrl::to(['/events/view', "id" => $n->id]) ?>">
                            <?= $n->name ?>
                            </a>
                        </div>
                        <div class="subinfo">
                            <!-- Курсов: 5 | Тестов: 2 | Материалов: 21 -->
                            <div class="date">
                                <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                            "time" => $n->begin_ts,
                                "formatType" => 2
                                ]) ?> - <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                            "time" => $n->end_ts,
                                "formatType" => 2
                                ]) ?>
                            </div>
                        </div>
                        <div class="add-info">
                            <?php if ($n->courses) { ?>
                            <div class="clearfix">
                                <div>
                                    <?php foreach($n->courses as $row) { ?>
                                    <div style="margin-bottom:15px;">
                                        <?php echo \app\widgets\ECourse\ECourse::widget([
                                                            "model" => $row->course
                                        ]);
                                        ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if ($n->tests) { ?>
                            <div class="clearfix">
                                <div>
                                    <?php foreach($n->tests as $row) { ?>
                                    <div style="margin-bottom:15px;" class="relative">
                                        <?php
                                        echo \app\widgets\ETest\ETest::widget([
                                        "model" => $row->test,
                                        "from" => new \common\models\From(['event',$n->id,'process']),
                                        "readonly" => $n->canEdit ? true : false
                                        ]);
                                        ?>

                                        <?php if ($result) { ?>
                                        <span style="position: absolute; right:5px; top:50%; font-size:24px; margin-top:-12px;"><i class="fa fa-<?=$result->result >= 50 ? "check" : "times"?> <?=$result->resultTextColor?>"></i></span>
                                        <?php } ?>

                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if ($n->materials) { ?>
                            <div class="clearfix">
                                <div>
                                    <?php foreach($n->materials as $row) { ?>
                                    <div style="" class="a-material">
                                        <?php
                                                        echo \app\widgets\EMaterial\EMaterial::widget([
                                                            'model' => $row->material,
                                        'backbone' => false,
                                        'type' => "media",
                                        'link' => ['target'=>'modal']
                                        ])
                                        ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?	}
                } else { ?>
                <div class="alert alert-warning"><?=Yii::t("main","Мероприятий не назначено")?></div>
                <?php } ?>
            </div>
        </div>

        <div class="asm-block">
            <div class="cont">
                <div class="title">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/library/index'])?>"><?=Yii::t("main","Результаты экзаменов")?></a>
                </div>
                <?php
                $current_date = null;
                if (!empty($lessons)) {
                    foreach ($lessons as $lesson) {
                        $lesson_results = array_filter($results, function($r) use ($lesson) {
                            return $lesson->id == $r->source_id;
                        });
                        if ($lesson_results) { ?>
                        <?php if ($current_date != $lesson->date) { ?>
                            <div class="labels">
                                <?=$lesson->date?>
                            </div>
                            <?php $current_date = $lesson->date; ?>
                        <?php } ?>
                        <?php
                            foreach ($lesson_results as $result) { ?>
                                <div style="margin-bottom:15px;" class="relative">
                                    <?php
                                    echo \app\widgets\ETest\ETest::widget([
                                        "model" => $result->test,
                                        "result" => $result,
                                        "readonly" => true
                                    ]);
                                    ?>
                                    <span style="position: absolute; right:5px; top:50%; font-size:24px; margin-top:-12px;"><i class="fa fa-<?=$result->result >= 50 ? "check" : "times"?> <?=$result->resultTextColor?>"></i></span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?	}
                } else { ?>
                    <div class="alert alert-warning"><?=Yii::t("main","Тестов не сдано")?></div>
                <?php } ?>

            </div>
        </div>
    </div>



</div>