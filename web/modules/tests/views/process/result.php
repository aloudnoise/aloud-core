<?php
$this->setTitle($test->name);
?>

<div class="action-content">

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
        <table class="answers-table mt-3">
            <%
            <?php if (SYSTEM == 'ttk') { ?>
                var literas = ["А","Б","В","Г","Д","Е","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
            <?php } else { ?>
                var literas = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
            <?php } ?>
            var n = 0;
            var selected_isset = 0;
            _(data.model.get("answers")).each(function(v) {

                var label = 'default';
                var bg = '';

                if (v.selected == 1 && v.is_correct == 1) {
                    label = 'success';
                    bg = 'table-success';
                }
                if (v.selected == 1 && !v.is_correct) {
                    label = 'danger';
                    bg = 'table-danger';
                }
                if (!v.selected && v.is_correct == 1) {
                    label = 'success';
                    bg = 'table-success';
                }

                if (v.selected == 1) {
                    selected_isset = 1;
                }

            %>
            <tr class="answer" style="cursor: pointer;" aid="<%=v.id%>" >
                <td class="<%=bg%> litera" style="width:50px"><span class="label label-<%=label%>"><%=literas[n]%></span></td>
                <td class="<%=bg%>" colspan="2" style="width:100%"><%=v.nameByLang%></td>
            </tr>
            <%
            n++;
            });
            if (!selected_isset) { %>
            <tr>
                <td colspan="2"><div class="alert alert-danger">Не выбрал ниодного ответа</div></td>
            </tr>
            <% } %>
        </table>
        </div>
    </script>

    <div class="white-block">
        <div class="questions">
        </div>
    </div>

</div>