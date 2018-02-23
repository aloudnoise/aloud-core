$(function() {

    TestsModule.BaseController = BaseController.extend({

        actionIndex : BaseAction.extend({
            afterRender : function() {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/tests/base/index'
                    }
                }, function(filter) {
                    filter.render();
                });


            }
        }),

        actionAdd : BaseAction.extend({

            afterRender : function() {

                var that = this;
                var newTestsModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Tests"
                });
                newTestsModel.setRules(this.controller.model.get("rules"));
                newTestsModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var form = new EForm({
                    el : $("#newTestsForm"),
                    model : newTestsModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }
                });
                form.render();

                $(that.el).find('input[name="tagsString"]').tagsinput({
                    typeaheadjs: {
                        source: function(q, sync) {
                            sync(that.controller.model.get("tags"));
                        }
                    }
                });

            }

        }),

        actionOptions: BaseAction.extend({
            afterRender: function() {

                var that = this;
                var testModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "TestOptions"
                });

                var form = new EForm({
                    el : $("#testOptionsForm"),
                    model : testModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                        }
                    }
                });
                form.render();

            }
        }),

    })

})