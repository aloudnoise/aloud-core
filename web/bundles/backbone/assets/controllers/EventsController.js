$(function() {
    EventsController = BaseController.extend({
        actionAdd : BaseAction.extend({
            afterRender : function() {

                var that = this;

               // $("#date").datepicker({dateFormat: 'dd.mm.yy'});
                var newEventModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Events"
                });
                newEventModel.setRules(this.controller.model.get("rules"));
                newEventModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                Yii.app.widget("EForm", {
                    el : $("#newEventForm"),
                    model : newEventModel,
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
                    startDate: this.controller.model.get("minDate")
                });

                $(that.el).find('input[name="tagsString"]').tagsinput({
                    typeaheadjs: {
                        source: function(q, sync) {
                            sync(that.controller.model.get("tags"));
                        }
                    }
                });

                $(that.el).find(".chosen-select").dynamicChosen();
                $(this.el).find(".chosen-container").width("100%");

            }
        })
    })
});