<div class="col col-auto position-relative">
    <a class="text-warning pointer border-warning" style="border-bottom:dashed 1px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?=$filter['year']?>
    </a>
    <div class="dropdown-menu">
        <?php
        $f = $filter->attributes;
        for ($i=date('Y')-2; $i<=date('Y'); $i++) {
            $f['year'] = $i;
            unset($f['month']);
            ?>
            <a class="dropdown-item" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/plans/index'], Yii::$app->request->get(), ['filter' => $f]))?>"><?=$i?></a>
        <?php } ?>
    </div>
</div>