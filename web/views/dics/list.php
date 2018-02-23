<?php

$this->addTitle(Yii::t("main","Список словарей"));

?>

<div class="action-content">

    <div class="row">
        <div class="col">
            <h3><?=Yii::t("main","Справочники")?></h3>
        </div>
    </div>

    <div class="mt-3 white-block">
        <div class="row">
            <div class="col">
                <input placeholder="<?=Yii::t("main","Поиск словаря")?>" type="text" value="<?=$filter->search?>" class="find-input form-control" />
            </div>
            <div class="col-auto">
                <a class="pointer find-button btn btn-outline-dark"><?=Yii::t("main","Найти")?></a>
            </div>
        </div>
    </div>

    <div class="white-block mt-3">
        <div class="card-columns">
            <?php if (!empty($dics)) {
                foreach ($dics as $dic) {
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <?=$dic->name?>
                            <div class="pull-right">
                                <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/dics/add", "dic"=>$dic->name])?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <?php if ($dic->description) { ?>
                            <div class="card-body">
                                <span class="text-muted"><?=$dic->description?></span>
                            </div>
                        <?php } ?>

                        <div class="card-body">
                            <ul class="list-group">
                                <?php if (!empty($dic->values)) {
                                    foreach ($dic->values as $value) {
                                        ?>
                                        <li class="list-group-item justify-content-between"><?=$value->nameByLang?>
                                            <div class="pull-right">
                                                <?php if ($value->isInOrganization) { ?>
                                                    <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/dics/add", "id"=>$value->id, "dic"=>$value->dic])?>" class="btn-link text-info"><i class="fa fa-pencil"></i></a>
                                                    <a confirm="<?=Yii::t("main","Вы уверены? Данное значение может использоваться в системе и его удаление приведет к фатальным последствиям")?>" href="<?=\app\helpers\OrganizationUrl::to(["/dics/delete", "id"=>$value->id])?>" class="btn-link text-danger"><i class="fa fa-trash"></i></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                } ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
            } ?>
        </div>
    </div>



</div>
