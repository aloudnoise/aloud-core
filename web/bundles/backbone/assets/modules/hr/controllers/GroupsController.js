$(function() {
    HrModule.GroupsController = BaseController.extend({
        actionIndex: BaseAction.extend({

            afterRender: function() {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/hr/groups/index'
                    }
                }, function(filter) {
                    filter.render();
                });

            }

        }),
        actionSearch : BaseAction.extend({
            afterRender: function() {
                var that = this;
                $(that.el).find(".find-input").keypress(function(e) {
                    if (e.keyCode == 13) {
                        $(that.el).find(".find-button").click();
                    }
                });
                //asd
                $(that.el).find(".find-button").click(function() {
                    filter = {};
                    filter.search = $(that.el).find(".find-input").val();
                    that.controller.model.get("GET").filter = filter;
                    var url = Yii.app.createOrganizationUrl("/hr/groups/search", that.controller.model.get("GET"));
                    Yii.app.navigate(url);
                });
            }
        }),
        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;

                var newGroupModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Groups"
                });

                Yii.app.widget("EForm", {
                    el : $("#newGroupForm"),
                    model : newGroupModel,
                    onSuccess : function(model,response) {
                        if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                        } else {
                            window.location.href = response.redirect;
                        }
                    }
                }, function(form) {
                    form.render();
                });
            }
        }),
    })
})