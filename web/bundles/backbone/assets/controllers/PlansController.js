$(function() {
    PlansController = BaseController.extend({
        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;

                // $("#date").datepicker({dateFormat: 'dd.mm.yy'});
                var newPlanModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "EducationPlans"
                });

                Yii.app.widget("EForm", {
                    el : $("#newPlanForm"),
                    model : newPlanModel,
                    onSuccess : function(model,response) {
                        window.location.href = response.redirect;
                    }
                }, function(form) {
                    form.render();
                });

                $('.date').datepicker({
                    isRTL: false,
                    format: 'dd.mm.yyyy',
                    autoclose:true,
                    language: that.controller.model.get("language"),
                });

            }
        }),
        actionView : BaseAction.extend({
            _initialize : function() {

                var that = this;

                var planModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "EducationPlans"
                });

                Yii.app.widget("EForm", {
                    el : $("#planForm"),
                    model : planModel,
                    onSuccess : function(model,response) {
                        Yii.app.navigate(response.redirect);
                    }
                }, function(form) {
                    form.render();
                });

            }
        })
    })
});