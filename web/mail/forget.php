<?php
use yii\helpers\Html;
use yii\helpers\Url;

$recoveryLink = Yii::$app->params['host'].'auth/recovery?fk='.$token->token;
?>

<div style="color: #ed1c24;font-size: 24px;font-family: arial narrow;line-height: 32px;border-top: 1px dashed #a1acbd;border-bottom: 1px dashed #a1acbd;margin-bottom: 15px; text-align: center;">
    <?php echo \Yii::t('main', 'Данные для входа в систему')?>
</div>

<h3 style="margin:22px;font-family:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;color:#2d3339;line-height:20px;padding-left: 20px;">
    <?php if (!empty($user->user->fio)) {?>
        <?= \Yii::t('main', 'Здравствуйте')?>, <?= $user->user->fio ?>!
    <?php } else { ?>
        <?= \Yii::t('main', 'Здравствуйте')?>!
    <?php } ?>
</h3>

<p style="line-height:22px;color:#727c84;padding-left: 20px;padding-right: 20px;">
        <p><?= \Yii::t('main', 'Вы запросили восстановление пароля. Для продолжнения, перейдите по ссылке:'); ?></p>

        <?= Html::a($recoveryLink, $recoveryLink) ?>
        <p><?= \Yii::t('main', 'Ссылка действительна в течении 24 часов.'); ?></p>

        <br>
    <em>
        <p><?php echo \Yii::t('main', 'Желаем Вам приятной работы!'); ?></p>
    </em>
</p>

