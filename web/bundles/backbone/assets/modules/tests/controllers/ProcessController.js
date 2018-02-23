$(function() {

    TestsModule.ProcessController = BaseController.extend({

        actionRun : BaseAction.extend({

            afterRender : function() {
                var that = this;

                $(that.el).find(".navigation").sticky({
                    topSpacing : 75
                });

                that.test = new BaseModel(that.controller.model.get("test"));
                that.model = new BaseModel(that.controller.model.get("model"), {
                    route : 'tests/process'
                })

                var timeLeftView = BaseItem.extend({
                    template : "#time_left_template",
                    data : "item",
                    afterRender : function() {
                        var that = this;
                    }
                })
                var timeLeft = new timeLeftView({
                    model : that.model
                });
                $(that.el).find(".time-left").html($(timeLeft.render().el));


                $(that.el).everyTime(1000, "timeleft", function() {
                    that.model.set("timeLeft", that.model.get("timeLeft") - 1);
                });

                that.model.on("change", function() {
                    if (that.model.get("isExpired") || that.model.get("timeLeft") <= 0) {
                        $(that.el).stopTime("timeleft");
                        Yii.app.navigate(Yii.app.createOrganizationUrl("/tests/process/finish", {id : that.model.get("id")}));
                    }
                });


                $(that.el).find(".questions").empty();
                $(that.el).find(".navigation .row").empty();

                _(that.controller.model.get("questions")).each(function(q) {
                    _(q.answers).each(function(ta) {
                        if (that.model.get("infoJson")['answers'] && typeof that.model.get("infoJson")['answers'][q.id] != "undefined") {
                            if (that.model.get("infoJson")['answers'][q.id].indexOf(ta.id) !== -1) {
                                ta.selected = 1;
                            }
                        }
                    });
                })

                var questions = new BaseCollection({
                }, {
                    yModel : "TestQuestion"
                })
                var n = 1;
                questions.on("add", function(m) {
                    m.set("n", n);
                    var view = new that.questionItem({
                        model : m,
                        parent : that
                    });
                    var nav = new that.navigationItem({
                        model : m,
                        parent : that
                    });
                    m.view = view;
                    m.nav = nav;
                    $(that.el).find(".questions").append($(m.view.render().el));
                    $(that.el).find(".navigation .items").append($(m.nav.render().el));
                    n++;
                });
                questions.set(that.controller.model.get("questions"));

            },
            questionItem : BaseItem.extend({
                template : "#question_template",
                data : "item",
                afterRender : function() {
                    var that = this;

                    $(that.el).find(".answer").click(function() {

                        if (that.model.get("correct_count") == 1) {
                            answers = _(that.model.get("answers")).where({
                                selected : 1
                            });
                            _(answers).each(function(a) {
                                a.selected = 0;
                            });
                        }

                        var answer = _(that.model.get("answers")).findWhere({
                            id : parseInt($(this).attr("aid"))
                        });
                        answer.selected = answer.selected == 1 ? 0 : 1;
                        that.model.trigger("change");
                        that.saveAnswer();
                    });

                },
                saveAnswer : function() {
                    var that = this;
                    $(that.el).stopTime("saveAnswer");
                    $(that.el).oneTime(500, "saveAnswer", function() {

                        var question = {
                            id : that.model.get("id"),
                            answers : _(_(that.model.get("answers")).where({
                                selected : 1
                            })).pluck("id")
                        }

                        that.parent.model.save({
                            question : question
                        });
                    })
                }
            }),
            navigationItem : BaseItem.extend({
                template : "#question_navigation_template",
                data : "item",
                afterRender : function() {
                    var that = this;
                    $(this.el).click(function() {
                        var body = $("html, body");
                        body.stop().animate({scrollTop:$(that.parent.el).find(".questions").find("#question_" + that.model.get("n")).offset().top - 100}, 500, 'swing', function() {
                        });
                    })

                }
            })

        }),

        actionResult : BaseAction.extend({

            afterRender : function() {
                var that = this;

                that.test = new BaseModel(that.controller.model.get("test"));
                that.model = new BaseModel(that.controller.model.get("model"), {})

                $(that.el).find(".questions").empty();

                _(that.controller.model.get("questions")).each(function(q) {
                    _(q.answers).each(function(ta) {
                        if (that.model.get("infoJson")['answers'] && typeof that.model.get("infoJson")['answers'][q.id] != "undefined") {
                            if (that.model.get("infoJson")['answers'][q.id].indexOf(ta.id) !== -1) {
                                ta.selected = 1;
                            }
                        }
                    });
                })

                var questions = new BaseCollection({
                }, {
                    yModel : "TestQuestion"
                })
                var n = 1;
                questions.on("add", function(m) {
                    m.set("n", n);
                    var view = new that.questionItem({
                        model : m,
                        parent : that
                    });
                    m.view = view;
                    $(that.el).find(".questions").append($(m.view.render().el));
                    n++;
                });
                questions.set(that.controller.model.get("questions"));

            },
            questionItem : BaseItem.extend({
                template : "#question_template",
                data : "item",
                afterRender : function() {
                }
            })
        }),

        actionDiscard: BaseAction.extend({

            afterRender: function() {

                var that = this;
                var discardModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "TestResults"
                });

                var form = new EForm({
                    el : $("#discardForm"),
                    model : discardModel,
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