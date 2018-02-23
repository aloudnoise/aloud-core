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
    <?php echo \Yii::t("main","Мероприятие")?>
</div>

<h3 style="margin-bottom:12px;font-family:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;color:#2d3339;line-height:20px;padding-left: 20px;">
    <?php
    echo \Yii::t("main","Уважаемый").', '.$attr['fio'];
    ?>
</h3>
<p style="line-height:22px;color:#727c84;padding-left: 20px;padding-right: 20px;">
    <em>
        <?=\Yii::t("main",'Приглашаем Вас принять участие в мероприятии "<strong>{name}</strong>", которое состоится {date}', ['name'=>$attr['name'], 'date'=>$attr['date']])?>.
        <?php  if (!empty($attr['info'])) { ?>
            <br> <?=$attr['info']?>
        <?php } ?>
    </em>
</p>