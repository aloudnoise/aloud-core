<?php $this->setTitle(Yii::t("main","Личный кабинет студента")) ?>
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
                                                            "from" => (new \common\models\From(['event', $n->id, 'process'])),
                                                            "readonly" => $n->canEdit ? true : false
                                                        ]);
                                                        ?>
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

            <div class="cont">
                <div class="title">
                    <?=Yii::t("main","Экзамены")?>
                </div>
                <?php
                $current_date = null;
                if (!empty($lessons)) {
                    foreach ($lessons as $lesson) { ?>
                        <?php if ($current_date != $lesson->date) { ?>
                            <div class="labels">
                                <?=$lesson->date?>
                            </div>
                            <?php $current_date = $lesson->date; ?>
                        <?php } ?>
                        <div style="margin-bottom:15px;" class="relative">
                            <?php
                            echo \app\widgets\ETest\ETest::widget([
                                "model" => $lesson->test,
                                "from" => new \common\models\From(['lesson',$lesson->id,'process']),
                                "readonly" => false
                            ]);
                            ?>
                        </div>
                    <?	}
                } else { ?>
                    <div class="alert alert-warning"><?=Yii::t("main","Экзаменов не назначено")?></div>
                <?php } ?>
            </div>

        </div>

        <div class="asm-block">
            <div class="cont">
                <div class="title">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/library/index'])?>"><?=Yii::t("main","Материалы")?></a>
                </div>
                <div class="labels">
                    Последние
                </div>
                <?php
                if (\Yii::$app->request->get("type") == 2) {
                    if (!empty($last_materials)) { ?>
                        <table class="table">
                            <tr>
                                <th colspan="2"><?=Yii::t("main","Наименование")?></th>
                                <th><?=Yii::t("main","Описание")?></th>
                                <th><?=Yii::t("main","Ключевые слова")?></th>
                                <th><?=Yii::t("main","Дата добавления")?></th>
                                <th><?=Yii::t("main","Просмотры")?></th>
                            </tr>
                            <?php foreach ($last_materials as $material) { ?>
                                    <?php
                                    echo \app\widgets\EMaterial\EMaterial::widget([
                                        "backbone" => false,
                                        "type" => "row",
                                        "model" => $material,
                                        "link" => [
                                            "target" => "modal"
                                        ]
                                    ]);
                                    ?>
                            <? } ?>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-warning"><?= Yii::t("main", "Экзаменов не назначено") ?></div>
                    <?php }
                } else {
                    if (!empty($last_materials)) { ?>
                        <?php foreach ($last_materials as $material) { ?>
                            <div class="mb-3">
                                <?php
                                echo \app\widgets\EMaterial\EMaterial::widget([
                                    "backbone" => false,
                                    "type" => "media",
                                    "model" => $material,
                                    "link" => [
                                        "target" => "modal"
                                    ]
                                ]);
                                ?>
                            </div>
                        <? } ?>
                    <?php } else { ?>
                        <div class="alert alert-warning"><?= Yii::t("main", "Экзаменов не назначено") ?></div>
                    <?php }
                }?>
            </div>
        </div>

    </div>

</div>