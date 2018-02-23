<?php if ($search !== false) { ?>
    <div class="form-group mb-3">

        <div class="row">
            <div class="col-lg col-md-12">
                <input type="text" class="form-control-sm form-control tag-find-input" placeholder="<?=Yii::t("main","Поиск по ключевым словам")?>" />
            </div>
            <div class="col-lg-auto col-md-12 mt-md-2 mt-lg-0">
                <a href="#" class="btn-block btn-sm pointer btn btn-outline-primary"><?=Yii::t("main","Поиск")?></a>
            </div>
        </div>

    </div>
<?php } ?>

<div class="tags-list">
    <?php if (!empty($tags)) { ?>
        <?php foreach ($tags as $i => $tag) {
            $filter = Yii::$app->request->get("filter", []);
            if ($filter['tags']) {
                if (in_array($tag, $filter['tags'])) {
                    $keys = array_flip($filter['tags']);
                    unset($keys[$tag]);
                    $filter['tags'] = array_flip($keys);
                    $color = 'text-warning';
                } else {
                    $filter['tags'][] = $tag;
                    $color = 'text-muted';
                }
            } else {
                $filter['tags'][] = $tag;
                $color = 'text-muted';
            }

            ?>
            <tag class="tag-item "><a class="inline-block mb-2 <?=$color?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge([$route], Yii::$app->request->get(), ['filter' => $filter]))?>"><?=$tag?></a><?php if ($i != count($tags) - 1) { ?><span class="tag-comma">,</span> <?php } ?></tag>
        <?php } ?>
    <?php } ?>
</div>
