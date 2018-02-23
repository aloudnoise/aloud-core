<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 11.10.16
 * Time: 18:40
 * To change this template use File | Settings | File Templates.
 */

?>


<div style="color: #ed1c24;font-size: 24px;font-family: arial narrow;line-height: 32px;border-top: 1px dashed #a1acbd;border-bottom: 1px dashed #a1acbd;margin-bottom: 15px; text-align: center;">
    <?php echo \Yii::t("main","Данные для входа в систему")?>
</div>

<h3 style="margin:22px;font-family:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;color:#2d3339;line-height:20px;padding-left: 20px;">
    <?php echo \Yii::t("main","Здравствуйте")?>, <?php echo $attr['fio'];?>!

</h3>
<p style="line-height:22px;color:#727c84;padding-left: 20px;padding-right: 20px;">
    <em>
        <p><?php echo \Yii::t("main","Ваши данные:"); ?></p>

        <?php echo \Yii::t("main","Логин: ").$attr['login']; ?><br>
        <?php echo \Yii::t("main","Пароль: ").$attr['password']; ?><br>
        <br>
        <p><?php echo \Yii::t("main","Желаем Вам приятной работы!"); ?></p>
    </em>
</p>

