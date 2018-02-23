<?php if (!Yii::$app->request->isAjax) $this->beginContent('@app/views/layouts/index.php'); ?>

    <div class="inner-wrapper">

        <?php if (!$this->context->isModal) { ?>

        <div class="pattern-background"></div>

        <div class="d-print-none">
            <?php echo $this->render("@app/views/layouts/main_header.twig", [
                "menu" => $menu
            ]); ?>
        </div>

        <div class="controller">
            <div class="crm-main-container">
                <div class="d-print-none">
                    <?=\app\widgets\EProfile\ESideProfile::widget([
                        'model' => \Yii::$app->user->identity
                    ])?>
                </div>
                <div class="inner-content">
                    <div class="p-3">
                        <?php if (!Yii::$app->request->get("from") OR !\common\models\From::instance()->getWidgetClass()) { ?>
                            <div class="row m-0">
                                <div class="<?=Yii::$app->data->layout_class ?: "col-12"?>">
                                    <?=$this->render("@app/views/common/controller", [
                                            'content' => $content
                                    ])?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?=(\common\models\From::instance()->getWidgetClass())::widget([
                                'from' => Yii::$app->request->get("from"),
                                'content' => $this->render("@app/views/common/controller", [
                                    'content' => $content
                                ])
                            ])?>
                        <?php } ?>
                    </div>
                </div>
            </div>
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