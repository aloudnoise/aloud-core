<table class="calendar table table-bordered border-0">

    <?php

        $next = \common\helpers\Common::calendar_1day($month, $year);

    ?>
    <tr class="calendar-row">
    <?php for ($x = 1; $x<=7; $x++) { ?>
        <td class="calendar-col p-0 border-0 position-relative text-center">
            <div class="text-muted mb-2"><?=Yii::t("main","d_".$x."_short")?></div>
        </td>
    <?php } ?>
    </tr>
    <?php for ($y = 1; $y<=6; $y++) { ?>
        <tr class="calendar-row">
        <?php for ($x = 1; $x <= 7; $x++) { ?>

            <?php
                $ts = mktime(0,0,0,$month, $next, $year);
                $color = ($next < 1 OR $next > \common\helpers\Common::days_in_month($month, $year)) ? "text-very-light-gray" : "";
                $bubble_color = 'warning';
                if (date('d.n', $ts) == $day.".".$month) {
                    $color = 'bg-warning text-white';
                    $bubble_color = 'light';
                }
            ?>

            <td class="calendar-col position-relative">
                <a href="<?=\app\helpers\OrganizationUrl::to(array_merge([$route], Yii::$app->request->get(), [
                    'filter' => [
                        'day' => date('d', $ts),
                        'month' => date('n', $ts),
                        'year' => date('Y', $ts)
                    ]
                ]))?>" class="position-absolute <?=$color?>" style="display: block; left:0; right:0; bottom:0; top:0">
                    <?php if ($models[$ts]) {
                        ?>
                        <div style="position: absolute; left:0; right:0; top:0; padding-top:5px; line-height:1;" class="row justify-content-center">
                            <?php if ($models[$ts] > 4) { ?>
                                <div class="col col-auto" style="padding:0 1px; margin-top:-5px;">
                                    <div class="calendar-bubble rounded text-<?=$bubble_color?>"><small><?=$models[$ts]?></small></div>
                                </div>
                            <?php } else { ?>
                                <?php for ($e = 1; $e<=$models[$ts]; $e++) { ?>
                                    <div class="col col-auto" style="padding:0 1px;">
                                        <div class="calendar-bubble rounded bg-<?=$bubble_color?>"></div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div style="left:0; top:50%; margin-top:-10px;" class="position-absolute w-100 text-center ">
                        <strong><?=date('d', $ts)?></strong>
                    </div>
                </a>
            </td>

        <?php
            $next++;
        } ?>
        </tr>
    <?php } ?>

</table>