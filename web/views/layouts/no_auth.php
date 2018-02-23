<?php if (!Yii::$app->request->isAjax) $this->beginContent('@app/views/layouts/index.php'); ?>

    <div class="no-auth inner-wrapper h-100" style="overflow:hidden;">

        <?php if (!$this->context->isModal) { ?>

            <video autoplay muted loop class="background-video">
                <source src="<?=Yii::$app->assetManager->getBundle("theme")->baseUrl."/media/background_mpeg.mp4"?>" type="video/mp4">
            </video>

            <div class="controller h-100">
                <?=$this->render("@app/views/common/controller", [
                    'content' => $content
                ])?>
            </div>

        <?php } else { ?>
            <?=$this->render("@app/views/common/controller", [
                'content' => $content
            ])?>
        <?php } ?>

    </div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <?php $this->endContent();
} ?>