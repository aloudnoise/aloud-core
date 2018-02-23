<?php $host =  $_SERVER['HTTP_HOST']; ?>


<table style="width: 700px;margin-left: auto;margin-right: auto;" cellspacing="0" cellpadding="0">
    <tbody><tr>
        <td colspan="3" style="height: 27px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 30px;">&nbsp;</td>
        <td>
            <table style="width: 100%;margin-bottom: 20px;" cellspacing="0" cellpadding="0">
                <tbody><tr>
                    <td style="padding-left: 20px;">
                        <img alt="КТЖ" src="<?=$host.Yii::$app->assetManager->getBundle("base")->baseUrl."/img/logo.png"?>" />
                    </td>
                    <td style="vertical-align: bottom;text-align: right;color: #768490;padding-right: 20px;">
                        <span style="padding-right: 10px;border-right: 1px solid #768490;margin-right: 10px;"><?php echo date("d.m.y"); ?></span>
                        <a href="<?=$host?>" style="color: #1c84c3;" target="_blank" rel="noopener"><?=$host?></a>
                    </td>
                </tr>
                </tbody></table>

            <table style="width: 100%;margin-bottom: 21px;background-color: #fff;" cellspacing="0" cellpadding="0">
                <tbody><tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 20px;"></td>
                    <td>
                        <?=$body?>

                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                </tbody></table>
        </td>
        <td style="width: 30px;">&nbsp;</td>
    </tr>
    </tbody></table>