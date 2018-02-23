<?php
$this->setTitle(Yii::t("main","Диалоги"), false);
?>

<div class="action-content">

    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Сообщения")?></h3>
                </div>
                <div class="col col-auto ml-auto">
                    <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/search', 'from' => ['dialog',null,'redirect','/messages/add']])?>" class="btn btn-primary"><?=Yii::t("main","Новое сообщение")?></a>
                </div>
            </div>

            <div class="mt-3 mb-3 filter-panel white-block">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень диалогов и чатов в группах")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Чтобы написать сообщение, выберите уже существующий диалог или нажмите кнопку {add_link}, и найдите пользователя, которому хотите написать.", [
                                'add_link' => "<a target='modal' class='text-warning' href='".\app\helpers\OrganizationUrl::to(['/hr/users/search', 'from' => ['dialog',null,'redirect','/messages/add']])."'>".Yii::t("main","Новое сообщение")."</a>"
                            ])?></p>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col">
                        <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Поиск сообщений")?>" type="text" value="<?=$filter->search?>" class="find-input form-control" name="search" />
                    </div>
                    <div class="col col-auto">
                        <a href="#"  data-role="filter" data-action="submit" class="pointer btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                    </div>
                </div>

            </div>

            <? $this->context->external = true; ?>
            <?=$this->context->runAction('list'); ?>

        </div>
    </div>

</div>
