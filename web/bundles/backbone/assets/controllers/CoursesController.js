$(function() {
    CoursesController = BaseController.extend({

        actionIndex : BaseAction.extend({

            afterRender : function() {

                var that = this;

                var filter  = this.controller.model.get("filter");

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/courses/index'
                    }
                }, function(filter) {
                    filter.render();
                });


            }

        }),

        actionAdd : BaseAction.extend({

            afterRender : function() {

                var that = this;
                console.log(this.controller.model);
                var newCourseModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Courses"
                });
                newCourseModel.setRules(this.controller.model.get("rules"));
                newCourseModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                Yii.app.widget("EForm", {
                    el : $("#newCoursesForm"),
                    model : newCourseModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
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

            }

        }),
        actionView : BaseAction.extend({
            lessons : null,
            _initialize : function() {

                var that = this;

                //$(this.el).find(".lessons-list").empty();

                this.lessons = new BaseCollection({}, {
                    yModel : "CourseLessons",
                    model : BaseModel.extend({
                        yModel : "CourseLessons",
                        schema : 'app/models'
                    })
                });

                var lessonItem = BaseItem.extend({
                    template : "#course_lesson_template",
                    data : "item"
                });

                var n = 1;

                if (this.controller.model.get("course_lesson")) {
                    var newCourseLessonModel = new BaseModel(this.controller.model.get("course_lesson"), {
                        yModel: "CourseLessons"
                    });

                    CKEDITOR.replace($(that.el).find(".lesson-textarea")[0], {
                        customConfig: 'config.js'
                    });

                    Yii.app.widget("EForm", {
                        el: $("#newCourseLessonForm"),
                        model: newCourseLessonModel,
                        options: {
                            uploader: false
                        },
                        onSuccess: function (model, response) {
                            if (response.redirect) {
                                Yii.app.navigate(response.redirect, 'normal', {
                                    scroll: false
                                });
                            }
                        }
                    }, function (form) {
                        form.render();
                        var body = $("html, body");
                        body.stop().animate({scrollTop:$(form.el).offset().top - 100}, 1000, 'swing', function() {
                        });

                    });
                }

            }
        }),
        actionViewder : BaseAction.extend({

            _initialize : function() {

                var that = this;

                var derframe = $(that.el).find("iframe.der");
                var unf = $(that.el).find("a.der-fullscreen-cancel");
                $(that.el).find("iframe.der").height($(window).innerHeight()*0.75);
                $(that.el).find("a.der-fullscreen").click(function() {
                    $(derframe).css({
                        position:"fixed",
                        height:"100%",
                        top:0,
                        bottom:0,
                        left:0,
                        right:0,
                        zIndex:9998
                    }).appendTo("body");
                    Yii.app.top = $(window).scrollTop();
                    $("body").css({overflow:"hidden"});
                    $(".wrapper").css({marginTop:"-"+Yii.app.top+"px"});

                    $(unf).appendTo("body").show();
                });

                $(unf).click(function() {
                    $(that.el).find(".material-content").append($(derframe));
                    $(that.el).find(".material-content").append($(unf));
                    $(derframe).attr("style", "width:100%;");
                    $(derframe).height($(window).innerHeight()*0.75);
                    $(this).hide();
                    $("body").css({overflow:"visible"});
                    $(".wrapper").css({marginTop:0});
                    $(window).scrollTop(Yii.app.top);
                })

            }
        }),
        actionAddtask : BaseAction.extend({
            afterRender : function() {

                var that = this;
                var newTaskModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Tasks"
                });
                newTaskModel.setRules(this.controller.model.get("rules"));
                newTaskModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                CKEDITOR.inline($(that.el).find(".task-textarea")[0], {
                    customConfig: 'config.js'
                });

                Yii.app.widget("EForm", {
                    el : $("#newTaskForm"),
                    model : newTaskModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        $(that.controller.el).modal("hide");
                    }
                }, function(form) {
                    form.render();
                });


            }
        })
    })

})