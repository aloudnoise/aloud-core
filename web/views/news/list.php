<div class="events-list mt-3">
    <?php if ($news) {
        /** @var \app\models\News $new */
        foreach ($news as $new) { ?>
            <div class="white-block list-item mb-3">

                <div class="row mt-2">
                    <div class="col">
                        <h5><a href="<?=\app\helpers\OrganizationUrl::to(["/news/view", "id" => $new->id])?>"><?php echo $new->name ?></a></h5>
                        <div class="text-muted py-4">
                            <?= $new->shortContent ?>
                        </div>
                    </div>

                    <?php if ($new->image) { ?>
                        <div class="ml-auto col-auto">
                            <img class="rounded-circle" src="<?=$new->image?>" />
                        </div>
                    <?php } ?>

                </div>

                <div class="row mt-2">
                    <div class="col-auto text-muted align-self-center">
                        <i class="fa fa-tags"></i> <?= $new->tagsString ?>
                    </div>
                    <div class="col-auto text-muted align-self-center">
                        <i class="fa fa-eye"></i> <?= $new->viewsCount ?>
                    </div>
                    <div class="col-auto ml-auto align-self-center">
                        <p class="date text-muted">
                            <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $new->getByFormat('ts', 'd.m.Y'),
                                    "formatType" => 2,
                                    "showTime" => false,
                                ]) ?>
                        </p>
                    </div>
                </div>

            </div>
            <?php
        }
    } ?>
</div>