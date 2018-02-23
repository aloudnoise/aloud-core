<table class="calendar table table-bordered border-0">
    <?php $next = 1; ?>
    <?php for ($y = 1; $y<=4; $y++) { ?>
        <tr class="calendar-row">
            <?php for ($x = 1; $x <= 3; $x++) { ?>

                <?php
                $ts = mktime(0,0,0,$next, 1, $year);
                $bubble_color = 'bg-warning';
                $color = "text-muted";
                if (date('n', $ts) == $month) {
                    $color = 'bg-warning text-white';
                    $bubble_color = 'bg-light';
                }
                ?>

                <td class="calendar-col-month position-relative">
                    <a href="<?=\app\helpers\OrganizationUrl::to(array_merge([$route], Yii::$app->request->get(), [
                        'filter' => [
                            'month' => date('n', $ts),
                            'year' => date('Y', $ts)
                        ]
                    ]))?>" class="position-absolute <?=$color?>" style="display: block; left:0; right:0; bottom:0; top:0">
                        <div style="left:0; top:50%; margin-top:-10px;" class="position-absolute w-100 text-center ">
                            <strong><?=Yii::t("main","m_".date('n', $ts))?></strong>
                            <?php if ($models[$ts]) {
                                ?>
                                <div class="mt-1 row justify-content-center">
                                    <?php for ($e = 1; $e<=$models[$ts]; $e++) { ?>
                                        <div class="col col-auto" style="padding:0 1px;">
                                            <div class="calendar-bubble rounded <?=$bubble_color?>"></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </a>
                </td>

                <?php
                $next++;
            } ?>
        </tr>
    <?php } ?>

</table>