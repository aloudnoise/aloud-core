<?php if ($this->context->material->mtype == \common\models\Materials::MTYPE_IMAGE) { ?>
    <div>
        <img src='<?=\common\helpers\Common::createUrl("/library/download", array("id"=>$this->context->material->id))?>' />
    </div>
<?php } else if ($this->context->material->mtype == \common\models\Materials::MTYPE_VIDEO) { ?>
    <div class="embed-responsive embed-responsive-16by9">
        <video src="<?=$this->context->material->infoJson['file']['url']?>" controls></video>
    </div>
<?php } else if ($this->context->material->mtype == \common\models\Materials::MTYPE_HTML) { ?>
    <a class="btn btn-warning der-fullscreen pointer text-white" style="margin-bottom:5px;"><?=Yii::t("main","Развернуть")?></a>
    <iframe class="der" src="<?=$this->context->material->infoJson['file']['url']?>" style="width:100%;"></iframe>
    <a style="z-index:9999; position: fixed; bottom:20px; right:60px; display:none;" class="btn btn-danger btn-sm der-fullscreen-cancel  pointer text-white"><?=Yii::t("main","Свернуть")?></a>
<?php } else if ($this->context->material->mtype == \common\models\Materials::MTYPE_AUDIO) { ?>
    <div class="clearfix">
        <audio src="<?=$this->context->material->infoJson['file']['url']?>" controls></audio>
    </div>
<?php } else { ?>
    <div style='position:relative' class='clearfix'>
        <div style='width:40px; height:50px;' class='inline-block file-icon'>
            <img src='<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/icons/".$this->context->material->icon?>.png' />
        </div>

        <a <?=isset($this->context->link['target']) ? "target='".$this->context->link['target']."'" : "target='_full'"?> style='margin-left:10px;' class='inline-block file-name' href='<?=$this->context->link['href'] ? $this->context->link['href'] : app\helpers\OrganizationUrl::to(["/library/download", "id"=>$this->context->material->id])?>'><?=$this->context->material->infoJson['file']['name']?></a>
        <?=isset($this->context->material->infoJson['file']['size']) ? "<span style='margin-top:16px;' class=\"pull-right\">".\common\helpers\Common::human_filesize($this->context->material->infoJson['file']['size'])."</span>" : ""?>

        <?php
        if ($this->context->material->mtype === \common\models\Materials::MTYPE_DOCUMENT OR $this->context->material->mtype === \common\models\Materials::MTYPE_PRESENTATION) {
            ?>
            <div class="embed-responsive embed-responsive-16by9 mt-3">
                <div class='clearfix'>
                    <iframe class="uploaded-document-iframe" src="https://docs.google.com/viewer?url=<?=$this->context->material->infoJson['file']['url']?>&embedded=true" style="border: none;"></iframe>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
<?php } ?>
