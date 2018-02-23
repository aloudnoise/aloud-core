<?php if (!Yii::$app->request->isAjax) $this->beginContent('@app/views/layouts/index.php'); ?>

    <div class="inner-wrapper">

        <div class="controller mb-3 mt-3">
            <div class="row justify-content-center m-0">
                <div class="<?=Yii::$app->data->layout_class ?: "col-xl-10 col-lg-11 col-md-12 col-sm-12"?>">
                    <div class=" crm-main-container">
                        <?=$content?>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <?php $this->endContent();
} ?>