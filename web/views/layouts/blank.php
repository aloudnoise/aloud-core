<?php if (!Yii::$app->request->isAjax) $this->beginContent('@app/views/layouts/index.php'); ?>

    <div class="inner-wrapper h-100">
        <div class="controller h-100">
            <div class="crm-main-container h-100">
                <div class="m-0 row mt-3 h-100 justify-content-center">
                    <div class="<?=Yii::$app->data->layout_class ?: "col-8"?> align-self-center">
                        <?=$this->render("@app/views/common/controller", [
                            'content' => $content
                        ])?>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <?php $this->endContent();
} ?>