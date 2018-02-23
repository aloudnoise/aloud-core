<div id="notifications_main">

    <div class="notifications-icon relative">
        <a style="font-size:1.4rem;" class="text-white" href="javascript:void(0);"><i class="fa fa-bell"></i></a>

        <div class="enotification-popover popover fade bs-popover-bottom" role="tooltip" style="display:none; width:270px; position:absolute; left:auto; right:-8px; top:30px; ">
            <div class="arrow" style="right:5px; margin-left:-5px;"></div>
            <div class="popover-header">
                <div class="row">
                    <div class="col-auto"><?=Yii::t("main","Только что")?></div>
                    <div class="col-auto ml-auto"><a class="text-muted pointer dismiss-popover"><i class="fa fa-times"></i></a></div>
                </div>
            </div>
            <div class="popover-body">

            </div>
        </div>

    </div>

    <script type="text/template" id="socket_notification_template">
        <div class="row my-2">
            <div class="col-auto">
                <% if (data.model.get('url')) { %>
                    <a href="<%=data.model.get('url')%>" class="icon-circle bg-<%=data.model.get('color')%>">
                        <i class="fa fa-<%=data.model.get('icon')%>"></i>
                    </a>
                <% } else { %>
                    <span class="icon-circle bg-<%=data.model.get('color')%>">
                        <i class="fa fa-<%=data.model.get('icon')%>"></i>
                    </span>
                <% } %>
            </div>
            <div class="col">
                <h6>
                    <% if (data.model.get('url')) { %>
                        <a href="<%=data.model.get('url')%>"><%=data.model.get("name")%></a>
                    <% } else { %>
                        <p><%=data.model.get("name")%></p>
                    <% } %>
                </h6>
                <p class="text-muted"><small><%=data.model.get('content')%></small></p>
            </div>
        </div>
    </script>

    <script type="text/template" id="notifications_icon_template">
        <div class='icon-inner'><a style='font-size:25px; <%=data.parent.collection.length > 0 ? "cursor:pointer" : "" %>' class='<%=data.parent.collection.length > 0 ? "link-purple" : "link-gray" %>' ><i class='fa fa-bell'></i><%
            if (data.parent.collection.length > 0) { %>
                <em class='sup'><%=data.parent.collection.length%></em>
            <% }
        %></a>
        </div>
    </script>

    <script type="text/template" id="notification_item_template">
        <% data = data.model.toJSON(); %>
        <div class="notification_body" sort-index='<%=data.ts%>' style='position:relative; margin:10px 0; padding:0px 5px 10px 5px; border-bottom:1px solid #ddd;'>

            <div style='margin-right:30px;'>
                <span class="notification_title"></span>
                <a href='<%=data.actionSite.url%>' class="notification_message"><%=data.actionSite.message%></a>
                <div style='margin-top:5px;' class="display-date"><?php echo \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time"=>"data.ts",
                        "type"=>\app\components\Widget::TYPE_TEMPLATE
                    ]); ?></div>
                <div class='clear'></div>
            </div>

            <span style='position:absolute; right:0px; top:0px;' class="close delete-notification"><i class='fa fa-times'></i></span>

            <div style="clear:both;"></div>
        </div>
    </script>

    <div style="display:none;" class='notifications-content'>

    </div>

</div>