<?php
$panel_url = ['/tests/constructor/compile', 'id' => $test ? $test->id : null, 'theme_id' => $theme ? $theme->id : null];
?>
<div class="instruments-panel">

    <div class="white-block border-warning">

        <div class="text-center mb-4">
            <h5><?=Yii::t("main","Панель инструментов")?></h5>
        </div>

        <div class="list-group" style="margin:0 -20px;">
            <?php
            $instruments = $panel->instruments;
            if (!$test) {
                unset($instruments['full_theme']);
            }
            foreach ($instruments as $name => $instrument) { ?>
                <a href="<?=\app\helpers\OrganizationUrl::to(array_merge($panel_url, ['panel' => ['instrument' => $name]]))?>" class="<?=$panel['instrument'] == $name ? "text-white bg-primary" : ""?> rounded-0 border-left-0 border-right-0 list-group-item list-group-item-action p-1">
                    <div class="row d-lg-flex d-md-none">
                        <div class="col-2 text-center align-self-center">
                            <i style="font-size:1.4rem;" class="fa fa-<?=$instrument['icon']?>"></i>
                        </div>
                        <div class="col">
                            <p style="line-height: 1;"><?=$instrument['label']?></p>
                            <p class="mt-1" style="line-height: 1;"><small class="<?=$panel['instrument'] == $name ? "" : "text-muted"?>"><?=$instrument['description']?></small></p>
                        </div>
                    </div>
                    <p class="py-3 px-2 d-lg-none d-md-block" title="<?=$instrument['description']?>"><i class="fa fa-<?=$instrument['icon']?>"></i> <?=$instrument['label']?></p>
                </a>
            <?php } ?>
        </div>

        <div class="mt-3">
            <div class="text-center">
                <a href="<?=\app\helpers\OrganizationUrl::to($test ? ['/tests/base/view', 'id' => $test->id] : ['/tests/questions/index', 'id' => $test->id])?>" class="btn btn-lg btn-block btn-outline-danger"><?=Yii::t("main","Назад")?></a>
            </div>
        </div>

    </div>
</div>