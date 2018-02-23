<script type="text/template" id="material_template">

    <div aid="<%=data.model.get("id")%>" class="assign-item material-item <%=Yii.app.currentController.target == "modal" ? "col-xl-6 col-lg-6 col-md-6 col-sm-6" : "col-xl-4 col-lg-4 col-md-4 col-sm-6"%>" style="margin-bottom:15px;">
        <a target="modal" href="<%=Yii.app.createOrganizationUrl("/library/view", {id : data.model.get("id")})%>" class="media no-hover list-item relative" style="display: block;  color:#333; margin-bottom:0; height:100%">
            <div class="media-left media-middle">
                <img class="media-object" src="<%=BASE_ASSETS + "/img/icons/" + data.model.get("icon") + ".png"%>" data-holder-rendered="true" style="max-width:none; width: 100px; height: 100px;" />
            </div>
            <div class="media-body">
                <h4 class="media-heading"><%=data.model.get("name")%></h4>
                <p><%=data.model.get("shortDescription")%></p>
                <p class="text-muted" style="margin-bottom:20px"><%=data.model.get("tagsString")%></p>
                <p class="text-muted" style="position:absolute; bottom:5px;">
                    <span>
                        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => "data.model.get('ts')",
                            "type" => \app\components\Widget::TYPE_TEMPLATE
                        ]); ?>
                    </span>
                    <span style="margin-left:15px;">
                        <i class="fa fa-eye"></i> <%=data.model.get('viewsCount')%>
                        <% if (data.model.get("type") == <?=\app\models\Materials::TYPE_FILE?>) { %>
                            <i class="fa fa-cloud-download" style="margin-left:5px;"></i> <%=data.model.get('downloadsCount')%>
                        <% } %>
                    </span>
                </p>
            </div>
        </a>
    </div>

</script>