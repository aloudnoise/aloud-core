<?php $this->setTitle(Yii::t("main","Назначенные материалы")) ?>
<div class="action-content">

    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Назначенные материалы")?></h3>
                </div>
            </div>

            <div class="filter-panel mt-3 white-block">
                <div class="row mb-3">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень материалов, которые назначены вам из мероприятий и курсов")?></p>
                    </div>
                </div>

                <?=$this->render("nav", [
                        'filter' => $filter
                ])?>

            </div>

            <?php if ($courses OR $materials) { ?>
                <div class="courses-container">
                    <?php foreach ($events as $ev) {
                        /* @var $e \common\models\Events */

                        $event_courses = array_filter($courses, function($c) use ($ev) {
                            return $c->target_id == $ev->id;
                        });

                        $event_materials = array_filter($materials, function($m) use ($ev) {
                            return $m->target_id == $ev->id;
                        });

                        if ($event_courses OR $event_materials) { ?>
                            <div class="mt-2 white-block">
                                <div class="row">
                                    <div class="col">
                                        <h5>
                                            <span class="text-muted"><?=Yii::t("main","Мероприятие: ")?></span><a href="<?=\app\helpers\OrganizationUrl::to(["/events/view", "id" => $ev->id])?>"><?php echo $ev->name ?></a>
                                        </h5>
                                        <p class="date mt-2 text-muted"><small><?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                                    "time" => $ev->getByFormat('begin_ts', 'd.m.Y'),
                                                    "formatType" => 2,
                                                    "showTime" => false,
                                                ]) ?> - <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                                    "time" => $ev->getByFormat('end_ts', 'd.m.Y'),
                                                    "formatType" => 2,
                                                    "showTime" => false,
                                                ]) ?></small></p>
                                    </div>
                                </div>
                            </div>
                            <?php if ($event_materials) { ?>
                                <?php foreach ($event_materials as $m) { ?>
                                    <?php $material = $m->material ?>
                                    <div class="white-block mt-1">
                                        <div class="ml-2">
                                            <?php echo \app\widgets\EMaterial\EMaterial::widget([
                                                "model" => $material,
                                                "backbone" => false,
                                                "type" => "media",
                                                'from' => new \common\models\From(['event', $ev->id, 'process'])
                                            ]); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php foreach ($event_courses as $ec) {
                                $t = $ec->course;
                                ?>
                                <div class="course-item list-item mt-1 white-block">
                                    <div class="ml-2">
                                        <div class="row">
                                            <div class="col">
                                                <h6>
                                                    <span class="text-muted"><?=Yii::t("main","Курс: ")?></span><a href="<?=\app\helpers\OrganizationUrl::to(["/courses/view", "id"=>$t->id])?>"><?=$t->name?></a>
                                                </h6>
                                            </div>
                                            <div class="col col-auto ml-auto align-self-center">
                                                <div class="row">
                                                    <div class="col col-auto">
                                                        <i class="text-muted fa fa-tasks"></i> <?=intval(count($t->lessons))?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mt-2 mb-2"><?= nl2br(\Yii::$app->request->get("from") ? $t->shortDescription : $t->description) ?></p>
                                        <div class="row">
                                            <div class="col col-auto text-muted">
                                                <i class="fa fa-tags"></i> <?= $t->tagsString?>
                                            </div>
                                            <div class="col col-auto text-muted">
                                                <i class="fa fa-eye"></i> <?=$t->viewsCount?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($t->lessons as $lesson) { ?>
                                    <div class="lesson-item list-item mt-1 white-block">
                                        <div class="ml-4">
                                            <div class="row">
                                                <div class="col">
                                                    <h6>
                                                        <span class="text-muted"><?=Yii::t("main","Урок: ")?></span> <?=$lesson->name?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <p class="text-muted mt-2 mb-2"><?=$lesson->content?></p>
                                        </div>
                                    </div>
                                    <?php foreach ($lesson->materials as $m) { ?>
                                        <?php $material = $m->material ?>
                                        <div class="white-block mt-1">
                                            <div class="ml-5">
                                                <?php echo \app\widgets\EMaterial\EMaterial::widget([
                                                    "model" => $material,
                                                    "backbone" => false,
                                                    "type" => "media",
                                                    'from' => new \common\models\From(['lesson', $lesson->id, 'process'])
                                                ]); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php }
                    } ?>
                </div>
            <?php } else { ?>
                <div class="white-block mt-2">
                    <div class="mb-0 alert alert-warning"><h5><?= Yii::t("main", "Материалов не назначено") ?></h5></div>
                </div>
            <?php } ?>

        </div>
    </div>


</div>