<?php
\aloud_core\web\bundles\jsoneditor\JsonEditorBundle::register($this);
?>

<div id="<?=$attribute?>" class="<?=$attribute?>-json-editor" style="height:500px;"></div>
<input type="hidden" name="<?=$attribute?>" value='<?=is_array($model->$attribute) ? json_encode($model->$attribute) : $model->$attribute?>' />