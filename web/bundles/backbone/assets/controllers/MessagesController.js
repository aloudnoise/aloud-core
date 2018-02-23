$(function() {

    MessagesController = BaseController.extend({

        actionIndex: BaseAction.extend({

            afterRender: function() {

            }

        }),

        actionAdd: BaseAction.extend({

            afterRender: function() {

                var that = this;
                var newMessageModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "DialogForm"
                });

                var form = new EForm({
                    el : $("#newMessageForm"),
                    model : newMessageModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                        $(that.controller.el).modal("hide");
                    }
                });
                form.render();

            }

        }),

        actionView: BaseAction.extend({

            afterRender: function() {

                var that = this;
                var newMessageModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "DialogForm"
                });

                Yii.app.widget("EForm", {
                    el : $("#newMessageForm"),
                    model : newMessageModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            Yii.app.navigate(response.redirect, null, {
                                scroll: false
                            });
                        }
                        $(that.controller.el).modal("hide");
                    }
                }, function(form) {
                    form.render();

                });

                $("html .chat-container").animate({ scrollTop: 100000 }, 1);

            }

        })

    })

});