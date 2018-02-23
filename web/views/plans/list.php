<div class="events-list mt-3">
    <?php if ($plans) {
        foreach ($plans as $plan) { ?>
            <div class="white-block list-item mb-3">
                <div class="row">
                    <div class="col">
                        <h5>
                            <a href="<?=\app\helpers\OrganizationUrl::to(["/plans/view", "id" => $plan->id])?>"><?php echo $plan->name ?></a>
                        </h5>
                    </div>
                </div>
                <?php if ($plan->description) { ?>
                    <div class="mt-2">
                        <p class="text-muted"><?=$plan->description?></p>
                    </div>
                <?php } ?>
                <div class="row mt-2">
                    <div class="col-auto">
                        <p class="date text-muted"><small><?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $plan->getByFormat('begin_ts', 'd.m.Y'),
                                    "formatType" => 2,
                                    "showTime" => false,
                                ]) ?> - <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $plan->getByFormat('end_ts', 'd.m.Y'),
                                    "formatType" => 2,
                                    "showTime" => false,
                                ]) ?></small></p>
                    </div>
                </div>
            </div>
            <?php
        }
    } ?>
</div>