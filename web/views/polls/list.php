<div class="events-list mt-3">
    <?php if ($polls) {
        /** @var \app\models\News $new */
        foreach ($polls as $poll) { ?>
            <div class="white-block list-item mb-3">

                <div class="row">
                    <div class="col">
                        <h5><?= $poll->name ?></a></h5>
                        <div class="text-muted py-4">

                            <?= $this->render("@app/views/common/poll_question", [
                                'poll' => $poll,
                                'results' => true
                            ]) ?>

                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-auto text-muted align-self-center">
                        <i class="fa fa-tags"></i> <?= $poll->tagsString ?>
                    </div>
                    <div class="col-auto align-self-center">
                        <?php if ($poll->status == \app\models\Polls::STATUS_INACTIVE) { ?>
                            <a title="<?= Yii::t("main", "Пользователи не видят данное голосование") ?>"
                               href="<?= \app\helpers\OrganizationUrl::to(['/polls/toggle', 'id' => $poll->id]) ?>"
                               class="btn btn-sm btn-outline-warning"><?= Yii::t("main", "Деактивирован") ?></a>
                        <?php } else { ?>
                            <a title="<?= Yii::t("main", "Пользователи видят данное голосование и могут голосовать") ?>"
                               href="<?= \app\helpers\OrganizationUrl::to(['/polls/toggle', 'id' => $poll->id]) ?>"
                               class="btn btn-sm btn-outline-success"><?= Yii::t("main", "Активирован") ?></a>
                        <?php } ?>
                    </div>
                    <div class="col-auto align-self-center">

                    </div>
                    <div class="col-auto ml-auto align-self-center">
                        <a href="<?= \app\helpers\OrganizationUrl::to(["/polls/add", "id" => $poll->id]) ?>"
                           class="btn btn-sm btn-outline-primary"><i class="fa fa-pencil"></i></a>
                        <a confirm="<?= Yii::t("main", "Вы уверены?") ?>"
                           href="<?= \app\helpers\OrganizationUrl::to(["/polls/delete", "id" => $poll->id]) ?>"
                           class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>
                    </div>
                    <div class="col-auto align-self-center">
                        <p class="date text-muted">
                            <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                "time" => $poll->getByFormat('ts', 'd.m.Y'),
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