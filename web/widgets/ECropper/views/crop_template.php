<?php
\aloud_core\web\bundles\tools\ToolsBundle::registerJCrop($this);
?>

<script type="text/template" id="cropper_modal_template">
    <div class="modal fade cropper-modal">
        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="modal-dialog" style="max-width:none;">
                    <div  class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                            <h3 class="crop_crop"><?= Yii::t('main', 'Вы можете обрезать картинку если хотите'); ?></h3>

                            <h3 class="crop_preview"><?= Yii::t('main', 'Выберите миниатюру {miniature}', [
                                    'miniature' =>"<span class='miniature_placeholder'></span>",
                                ]); ?></h3>

                        </div>
                        <div  rel="popover"
                              data-placement="right"
                              data-trigger='manual'
                              data-content="asd"
                              data-animation="false"
                              data-html="true"
                              data-title="" class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success sendAfterCrop"><?=Yii::t('main', 'Сохранить')?></button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=Yii::t('main', 'Отмена')?></button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        </div>

    </div><!-- /.modal -->
</script>
