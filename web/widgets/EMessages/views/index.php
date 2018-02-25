<div style="display:none" class="sound"></div>

<script type="text/template" id="message_template">

    <div class="white-block mt-1">
        <div class="d-block message-item" message_id="<%=data.id%>">

            <div class="user-profile">
                <?=\aloud_core\web\widgets\EProfile\EProfile::widget([
                    'model' => "data.user",
                    'type' => \aloud_core\web\components\Widget::TYPE_TEMPLATE
                ])?>
            </div>


            <div class="message-content px-5 pt-3 pb-2">

                <p><%=data.message%></p>

            </div>

            <div class="row mt-2">
<!--                <div class="col-auto ml-auto">-->
<!--                    <p class="date text-muted">-->
<!--                        <small>-->
<!--                            --><?//= \aloud_core\web\widgets\EDisplayDate\EDisplayDate::widget([
//                                "time" => "data.ts",
//                                "formatType" => 1,
//                                "type" => \aloud_core\web\components\Widget::TYPE_TEMPLATE
//                            ]) ?>
<!--                        </small>-->
<!--                    </p>-->
<!--                </div>-->
            </div>

        </div>
    </div>

</script>