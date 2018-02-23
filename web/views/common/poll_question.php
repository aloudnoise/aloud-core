<div class="poll-question list-item">
    <div class="row">
        <div class="col">
            <h6><?= $poll->question ?></h6>
        </div>
    </div>
    <div class="answers-list mt-3">
        <?php

        if ($results) {
            $percents = $poll->getResultPercents();
        }

        if (!empty($poll->answers)) {
            foreach ($poll->answers as $index => $answer) {
                ?>
                <div class="row mb-2">
                    <div class="<?= $results ? "col-6" : "col" ?>">
                        <?php if ($vote) { ?>
                            <a href="<?= \app\helpers\OrganizationUrl::to(['/polls/vote', 'a' => $index, 'id' => $poll->id, 'return' => Yii::$app->request->url]) ?>"><?= $answer['name'] ?></a>
                        <?php } else { ?>
                            <?= $answer['name'] ?>
                        <?php } ?>
                    </div>
                    <?php if ($results) { ?>
                        <div class="col-6">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?= $percents[$index] ?>%;"
                                     aria-valuenow="<?= $percents[$index] ?>" aria-valuemin="0"
                                     aria-valuemax="100"><?= $percents[$index] ?>%
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>