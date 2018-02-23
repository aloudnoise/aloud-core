<a <?=isset($this->context->link['target']) ? "target='".$this->context->link['target']."'" : ""?> <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> href="<?=$this->context->link['href'] ? $this->context->link['href'] : app\helpers\OrganizationUrl::to(["/library/view", "id"=>$this->context->model->id])?>">

    <div style='width:40px; height:50px;' class='inline-block file-icon'>
        <img src='<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/icons/".$this->context->model->icon?>.png' />
    </div>

    <span class="title"><?php echo $this->context->model->shortName; ?></span>

</a>