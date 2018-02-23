<?php
$this->addTitle(Yii::t("main","Новости"));
?>

<div class="action-content">
    <div class="row big-gutter">

        <div class="col-8">

            <div class="row mb-3">
                <div class="col">
                    <h3><?=Yii::t("main","Новости")?></h3>
                </div>
                <?php if (Yii::$app->user->can("admin")) { ?>
                    <div class="col col-auto ml-auto">
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/news/add"])?>" class="btn btn-primary">
                            <?=Yii::t("main","Добавить новость")?>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <?php $this->context->external = true;?>
            <?= $this->context->runAction('list')?>
        </div>
        <div class="col-4">

            <div class="row mb-3">
                <div class="col">
                    <h3 class="mb-0"><?=Yii::t("main","Фильтры")?></h3>
                </div>
            </div>

            <div class="white-block">
                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/news/index'
                ])?>
            </div>
        </div>
    </div>
</div>


