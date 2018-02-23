$(function() {
    ReportsController = BaseController.extend({

        actionIndex : BaseAction.extend({

            afterRender : function() {

                var that = this;

                var filter = that.controller.model.get("filter");

                $(this.el).find('.type-select').change(function() {
                    Yii.app.navigate($(this).val(), null, {
                        scroll: false
                    });
                })

                $(this.el).find(".from-input, .to-input").datepicker({
                    format: "dd.mm.yyyy",
                    autoclose: true,
                    language: that.controller.model.get("language")
                });
                $(this.el).find(".from-input, .to-input").mask("99.99.9999");

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/reports/index'
                    }
                }, function(filter) {
                    filter.render();
                });


            }

        })

    })

})