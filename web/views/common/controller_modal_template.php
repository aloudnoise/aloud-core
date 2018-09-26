<script type="text/template" id="controller_modal_template">
    <div class="modal <%=data.controller%> <%=options.no_fade ? "" : "fade"%>" id="controller_modal<%=options.transaction ? '' : '_non_transaction'%>" data-backdrop="<%=data.modalBackdrop ? data.modalBackdrop : 'static'%>" tabindex="-1" role="dialog" aria-labelledby="controller_modal" aria-hidden="true">
        <div class="modal-dialog <%= data.size ? "modal-" + data.size : "" %> <%= data.classes ? data.classes : "" %>">
            <div class="modal-content <%= data.content_classes ? data.content_classes : "" %>">
                <% if (!data.noHeader) { %>
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><%= data.pageTitle %></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                <% } %>
                    <div class="modal-body">
                        <%= html %>
                    </div>
            </div>
        </div>
    </div>
</script>
