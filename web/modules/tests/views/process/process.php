<?php
(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);
$this->setTitle($test->name);
?>

<div class="action-content">

    <script type="text/template" id="time_left_template">
        <div class="time-left text-center bg-primary text-white" style="padding:5px; margin-bottom:10px;">
            <%
            var time = data.model.get("timeLeft");
            %>
            <div class="inline-block">
                <p><?=Yii::t("main","Время")?>:</p>
            </div>
            <div class="inline-block">
                <p><strong> <%=Math.floor(time/60) < 10 ? "0" + Math.floor(time/60) : Math.floor(time/60)%> : <%=(time % 60) < 10 ? "0" + (time % 60) : (time % 60)%></strong></p>
            </div>
        </div>
    </script>

    <script type="text/template" id="question_template">
        <div class="cont question question-item list-item mb-3"  id="question_<%=data.model.get("n")%>">
            <table style="width:100%" class="questions-table">
                <tr class="question-row">
                    <td style="width:80px" valign="top">
                        <span class="label label-warning"><%=data.model.get("n")%></span>
                    </td>
                    <td style="width:100%">
                        <%=data.model.get("nameByLang")%>
                    </td>
                </tr>
            </table>
            <table class="answers-table mt-1">
                <%
                <?php if (SYSTEM == 'ttk') { ?>
                    var literas = ["А","Б","В","Г","Д","Е","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
                <?php } else { ?>
                    var literas = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
                <?php } ?>
                var n = 0;
                _(data.model.get("answers")).each(function(v) {

                    %>
                    <tr class="answer" style="cursor: pointer;" aid="<%=v.id%>" >
                        <td class="<%=v.selected == 1 ? "bg-primary text-white" : ""%> litera" style="width:50px"><span class="label label-<%=v.selected == 1 ? "info" : "default"%>"><%=literas[n]%></span></td>
                        <td class="<%=v.selected == 1 ? "bg-primary text-white" : ""%>" colspan="2" style="width:100%"><%=v.nameByLang%></td>
                    </tr>
                    <%
                    n++;
                });
                %>
            </table>

            <hr />

        </div>
    </script>

    <script type="text/template" id="question_navigation_template">
        <div class="inline-block" style="margin:0px 2.5px 5px 2.5px;">
            <%
            var selected = _(data.model.get("answers")).findWhere({
                        selected : 1
                    });
            %>
            <a style="width:33px;" class="pointer text-white btn btn-sm btn-<%=selected ? "primary" : "warning" %>"><%=data.model.get("n")%></a>
        </div>
    </script>

    <div class="row">
        <div class="col-3">
            <div>
                <div class="navigation white-block">
                    <div class="time-left">

                    </div>

                    <div class="items text-center">

                    </div>

                    <div class="finish-test" style="margin-top:15px; text-align: center;">
                        <a target="_full" confirm="<?=Yii::t("main","Вы уверены? Вопросы на которые вы не ответили, не будут засчитаны.")?>" href="<?=app\helpers\OrganizationUrl::to(["/tests/process/finish", "id"=>$model->id])?>" class="btn btn-danger btn-lg"><?=Yii::t("main","Завершить")?></a>
                    </div>
                </div>
            </div>
        </div>

    	<div class="col-9">
            <div class="white-block">
                <div class="questions">
                </div>
            </div>
    	</div>
    </div>

</div>