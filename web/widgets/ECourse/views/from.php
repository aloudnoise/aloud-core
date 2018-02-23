<div class="row">
    <div class="col-2">
        <div class="side-event white-block">
            <div class="row justify-content-center">
                <div class="col-7">
                    <img style="max-width:100%" src="<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/menu/courses.png"?>" />
                </div>

                <div class="col-12 mt-5 text-center">
                    <h4><a href="<?=\app\helpers\OrganizationUrl::to(['/courses/view', 'id' => $this->context->model->id, 'from' => 0, 'return' => 0])?>"><?=$this->context->model->name?></a></h4>
                </div>

                <div class="col-12 mt-3 text-center text-very-light-gray">
                    <?=$this->context->model->shortDescription?>
                </div>

                <div class="col-12 mt-3 text-center text-very-light-gray">
                    <span class="">
                        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => (new DateTime($this->context->model->ts))->format('d.m.Y'),
                            'showTime' => false,
                            "formatType" => 2
                        ]) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-2">
            <?=\app\widgets\EProfile\ESideProfile::widget([
                'model' => \Yii::$app->user->identity
            ])?>
        </div>

    </div>
    <div class="col-10">

        <?php if ($lesson) { ?>
            <div class="white-block mb-2">
                <h5 class="text-purple"><?= $lesson->name ?>
            </div>
        <?php } ?>

        <?=$this->context->content?>
    </div>
</div>