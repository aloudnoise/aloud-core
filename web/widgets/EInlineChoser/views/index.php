<?= \yii\helpers\Html::beginTag("div", $this->context->htmlOptions) ?>
    <div class="choser-list">
        <?php
        $i = 0;
        foreach ($this->context->list as $l) {
            $i++;
            ?>
            <p class="choser-item <?=in_array($l->id, ($this->context->values  ? : [])) ? "text-primary active" : "text-muted"?>" vid="<?=$l->id?>"><?=$l->{$this->context->list_label_attribute}?></p><?=(count($this->context->list) > $i) ? "," : ""?>
        <?php
        }
        ?>
    </div>
    <input type="hidden" data-type="json" value='<?=json_encode($this->context->values)?>' class="choser-input" name="<?=$this->context->name?>" />
<?= \yii\helpers\Html::endTag("div"); ?>