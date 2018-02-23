<?php
$this->addTitle(Yii::t("main","Голосования"));
?>

<div class="action-content">
    <div class="row big-gutter">
        <div class="col-8">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Голосования")?></h3>
                </div>
                <?php if (Yii::$app->user->can("admin")) { ?>
                    <div class="col col-auto ml-auto">
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/polls/add"])?>" class="btn btn-primary">
                            <?=Yii::t("main","Добавить голосование")?>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <div class="mt-3 filter-panel white-block">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень голосований")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового голосования, нажмите кнопку {add_link}, и заполните необходимые поля: название, вопрос, ответы. В списке голосований вы можете открывать и закрывать голосования, а также смотреть результаты.", [
                                'add_link' => "<a class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/polls/add"])."'>".Yii::t("main","Добавить голосование")."</a>"
                            ])?></p>
                    </div>
                </div>
            </div>
            <?php $this->context->external = true;?>
            <?= $this->context->runAction('list')?>
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Фильтры")?></h3>
                </div>
            </div>
            <div class="white-block mt-3">
                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/polls/index'
                ])?>
            </div>
        </div>
    </div>
</div>


