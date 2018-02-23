$(function() {

    PollsController = BaseController.extend({

        actionIndex : BaseAction.extend({
            afterRender : function()
            {

            }
        }),

        actionView : BaseAction.extend({
            afterRender : function() {

            }
        }),

        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;

                var pollModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Polls"
                });

                Yii.app.widget("EForm", {
                    el : $("#newPollForm"),
                    model : pollModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        Yii.app.navigate(response.redirect);
                    }
                }, function(form) {
                    form.render();
                });

                $(that.el).find('input[name="tagsString"]').tagsinput({
                    typeaheadjs: {
                        source: function(q, sync) {
                            sync(that.controller.model.get("tags"));
                        }
                    }
                });

                var reIndexAnswers = function () {
                    var i = 1;
                    $(that.el).find(".answer").each(function () {
                        $(this).attr("i", i);
                        $(this).find(".answer-name").attr("name", "answers[" + i + "][name]");
                        i++;
                    })
                };

                $(that.el).find(".answers").find(".delete-variant").live("click", function () {
                    $(this).parents(".answer").remove();
                    reIndexAnswers();
                });

                $(that.el).find(".answers").find(".add-variant").live("click", function () {
                    var new_variant = $(this).parents(".answer").clone();
                    $(this).parents(".answer").after(new_variant);
                    reIndexAnswers();
                });


            }
        })

    })

})