$(function() {
    MainController = BaseController.extend({
        actionIndex: BaseAction.extend({
            _initialize: function () {

            }
        }),
        actionSupport: BaseAction.extend({

            afterRender: function() {

                var that = this;
                var newSupportModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Support"
                });

                CKEDITOR.replace($(that.el).find(".support-textarea")[0], {
                    customConfig: 'config.js'
                });

                var form = new EForm({
                    el : $("#newSupportForm"),
                    model : newSupportModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        $(that.controller.el).modal("hide");
                    }
                });
                form.render();

            }

        })
    })
})