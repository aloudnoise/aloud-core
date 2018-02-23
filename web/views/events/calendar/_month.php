<div class="col col-auto position-relative">
    <a class="text-warning pointer border-warning" style="border-bottom:dashed 1px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?=Yii::t("main","m_".$filter['month'])?>
    </a>
    <div class="dropdown-menu">
        <?php
        $f = $filter->attributes;
        for ($i=1; $i<=12; $i++) {
            $f['month'] = $i;
            unset($f['day']);
            ?>
            <a class="dropdown-item" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/events/index'], Yii::$app->request->get(), ['filter' => $f]))?>"><?=Yii::t("main","m_".$f['month'])?></a>
        <?php } ?>
    </div>
</div>