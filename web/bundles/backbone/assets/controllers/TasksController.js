$(function () {

    TasksController = BaseController.extend({

        actionIndex : BaseAction.extend({

            afterRender : function() {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/tasks/index'
                    }
                }, function(filter) {
                    filter.render();
                });

            }

        }),

        actionAdd : BaseAction.extend({

            afterRender : function() {

                var that = this;
                var newTasksModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Tasks"
                });
                newTasksModel.setRules(this.controller.model.get("rules"));
                newTasksModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                CKEDITOR.replace($(that.el).find(".task-textarea")[0], {
                    customConfig: 'config.js'
                });

                $(that.el).find(".uploaded-list").empty();
                that.files = new BaseCollection();
                that.files.on("add", function(m) {
                    var file = BaseItem.extend({
                        template : "#file_template",
                        data : "item",
                        events : {
                            "click .delete-file" : function() {
                                this.remove();
                            }
                        }
                    });
                    var view = new file({
                        model : m
                    });

                    m.view = view;
                    $(that.el).find(".uploaded-list").append($(view.render().el));

                });
                that.files.on("remove", function(m) {
                    m.view.remove();
                });

                that.files.set(newTasksModel.get("files"));

                var EFileUploader = new EUploader({
                    el : $(this.el).find(".uploader"),
                    template : false,
                    options : {
                        multiple : false,
                        cropper : false,
                        video : false,
                        uploadFileContainer : $(this.el).find(".uploader").find(".uploaded-loader"),
                        uploadFileTemplate : "#attached_file_template",
                        onSuccess : function(file) {
                            that.files.add(file.model.get("response"));
                            file.remove();
                        }
                    }
                });
                EFileUploader.newFile = function(file) {
                    var m = new EFileModel({
                        "name" : file.name,
                        "file" : file
                    })

                    EFileUploader.collection.set([m]);

                };
                EFileUploader.render();

                var form = new EForm({
                    el : $("#newTasksForm"),
                    model : newTasksModel,
                    options : {
                        uploader : EFileUploader
                    },
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                        $(that.controller.el).modal("hide");
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

        actionView : BaseAction.extend({
            afterRender : function() {

            }
        }),

        actionProcess : BaseAction.extend({
            afterRender : function() {

                var that = this;

                that.model = new BaseModel(that.controller.model.get("model"));

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
                        Yii.app.navigate(Yii.app.createOrganizationUrl("/tasks/finish", {id : that.model.get("id")}));
                    }
                });

                var taskResults = new BaseModel(this.controller.model.get("model"),{
                    yModel : "TaskResults"
                });

                CKEDITOR.replace($(that.el).find(".task-result-textarea")[0], {
                    customConfig: 'config.js'
                });

                var form = new EForm({
                    el : $("#TaskResultsForm"),
                    model : taskResults,
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

        actionCheck : BaseAction.extend({
            afterRender : function() {
                var that = this;

                $(that.el).find(".task-result-item input.task-result-input").change(function() {

                    var id = $(this).parents(".task-result-item").attr("task_id");
                    var model = _(that.controller.model.get('models')).findWhere({
                        id : parseInt(id)
                    });

                    if(model) {
                        var taskResult = new BaseModel({
                            id : id,
                            result : $(this).val()
                        }, {
                            route : 'tasks/check'
                        });

                        taskResult.save({}, {
                            success: function (model, response, xhr) {
                                taskResult.callSuccess(Yii.t("main", "Оценка сохранена/изменена"));
                            },
                            error : function(m, response) {
                                m.callError(response);
                            }
                        });
                    }
                });
            }
        }),

        actionEstimate: BaseAction.extend({
            afterRender: function() {

                var that = this;

                var taskResults = new BaseModel(this.controller.model.get("model"),{
                    yModel : "TaskResults"
                });

                var form = new EForm({
                    el : $("#newEstimateForm"),
                    model : taskResults,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        $(that.controller.el).modal("hide");
                    }
                });
                form.render();

            }
        }),

        actionOptions: BaseAction.extend({
            afterRender: function() {

                var that = this;
                var testModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "TaskOptions"
                });

                var form = new EForm({
                    el : $("#taskOptionsForm"),
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

        actionDiscard: BaseAction.extend({

            afterRender: function() {

                var that = this;
                var discardModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "TaskResults"
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

});