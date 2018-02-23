$(function() {
    OrganizationsController = BaseController.extend({
        actionAssign: BaseAction.extend({
            _initialize: function () {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/organizations/assign'
                    }
                }, function(filter) {
                    filter.render();
                });


            }
        })
    })
});