<div class="row">
        <div class="col">
            <div class="btn-group">
                <?php
                $get = \Yii::$app->request->get();
                if (Yii::$app->user->can("base_teacher")) {
                    $get['filter']['pr'] = 1;
                    ?>
                    <a class="font-weight-6 btn btn-light nav-link <?= $filter->pr == 1 ? "text-warning" : "text-muted" ?>"
                       href="<?= \app\helpers\OrganizationUrl::to(array_merge(["/library/index"], $get)) ?>"><?= Yii::t("main", "Мои материалы") ?></a>
                    <?php
                } else {
                    ?>
                    <a class="font-weight-6 btn btn-light nav-link <?=$this->context->action->id == "assigned" ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/library/assigned"],$get))?>"><?=Yii::t("main","Назначенные")?></a>
                    <?php
                }
                $get['filter']['pr'] = 2;
                ?>
                <a class="font-weight-6 btn btn-light nav-link <?=$filter->pr == 2 ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/library/index"],$get))?>"><?=Yii::t("main","Все материалы")?></a>
            </div>
        </div>

</div>