$(function() {
    HrModule.UsersController = BaseController.extend({
        actionIndex : BaseAction.extend({

            afterRender: function() {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/hr/users/index'
                    }
                }, function(filter) {
                    filter.render();
                });

                $(that.el).find(".show-columns-filter").click(function() {
                    $(that.el).find(".columns-filter").slideToggle(0);
                })

                $('.date').datepicker({
                    isRTL: false,
                    format: 'dd.mm.yyyy',
                    autoclose:true,
                    language: that.controller.model.get("language"),
                });


            }

        }),
        actionAssign : BaseAction.extend({

            afterRender: function() {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/hr/users/assign'
                    }
                }, function(filter) {
                    filter.render();
                });

                $(that.el).find(".show-columns-filter").click(function() {
                    $(that.el).find(".columns-filter").slideToggle(0);
                })

                $('.date').datepicker({
                    isRTL: false,
                    format: 'dd.mm.yyyy',
                    autoclose:true,
                    language: that.controller.model.get("language")
                });


            }

        }),
        actionSearch : BaseAction.extend({
            afterRender: function() {
                var that = this;
                $(that.el).find(".find-input").keypress(function(e) {
                    if (e.keyCode == 13) {
                        $(that.el).find(".find-button").click();
                    }
                })
                $(that.el).find(".find-button").click(function() {
                    filter = {};
                    filter.search = $(that.el).find(".find-input").val();
                    that.controller.model.get("GET").filter = filter;
                    var url = Yii.app.createOrganizationUrl("/hr/users/search", that.controller.model.get("GET"));
                    Yii.app.navigate(url);
                });
            }
        }),
        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;

                var newUserModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "UserForm"
                });

                Yii.app.widget("EForm", {
                    el : $("#newUserForm"),
                    model : newUserModel,
                    onSuccess : function(model,response) {
                        if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                        } else {
                            window.location.href = response.redirect;
                        }
                    }
                }, function(form) {
                    form.render();
                });

                $(this.el).find("input[name='phone']").mask("+9(999)9999999");

            }
        }),
        actionOptions: BaseAction.extend({
            afterRender: function() {

                var that = this;
                var userModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "UserOptions"
                });

                var form = new EForm({
                    el : $("#userOptionsForm"),
                    model : userModel,
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
        actionProfile: BaseAction.extend({
            afterRender: function() {

                var that = this;

                var model = new BaseModel(this.controller.model.get("model"), {
                    yModel: "Users"
                });
                model.setRules(this.controller.model.get("rules"));
                model.setAttributeLabels(this.controller.model.get("attributeLabels"));

                if ($("#profileForm").length) {
                    var profileForm = new EForm({
                        el: $("#profileForm"),
                        model: model,

                        onSuccess: function (model, response) {
                            if (that.controller.target == "modal") {
                                $(that.controller.el).modal("hide");
                            } else {
                                window.location.href = response.redirect;
                            }
                        }
                    }).render();

                    var EFileUploader = new EUploader({
                        el: $(this.el).find(".uploader"),
                        template: false,
                        options: {
                            multiple: false,
                            cropper: new ECropper({
                                options: {
                                    crop: {
                                        "preview": [
                                            "150",
                                            "150"
                                        ]
                                    }
                                }
                            }),
                            video: false,
                            uploadFileContainer: $(this.el).find(".uploader").find(".uploaded-loader"),
                            uploadFileTemplate: "#attached_file_template",
                            allowedExtensions: [
                                'jpg', 'png'
                            ],
                            onSuccess: function (uploader) {
                                var response = uploader.model.get("response")
                                $(profileForm.el).find('.uploaded-photo').html(
                                    '<img src="' + response.preview + '">'
                                );

                                $(profileForm.el).find("input[name='photo']").val(
                                    JSON.stringify(uploader.model.get("response"))
                                );
                            }
                        }
                    });

                    EFileUploader.newFile = function (file) {
                        var m = new EFileModel({
                            "name": file.name,
                            "file": file
                        });

                        EFileUploader.collection.set([m]);
                    };
                    EFileUploader.render();
                }

            }
        }),

        actionImport: BaseAction.extend({

            _initialize: function() {

                var that = this;

                // Initiate Uploaders
                var EFileUploader = new EUploader({
                    el : $(this.el).find(".import-uploader-block"),
                    options : {
                        multiple : false,
                        cropper : false,
                        video : false,
                        uploadFileTemplate : "#uploaded_file_template",
                        onSuccess : function(file) {
                            var response = file.model.get("response");
                            $(that.el).find("#import_document").val(response.url);
                        }
                    }
                });
                EFileUploader.newFile = function(file) {
                    var m = new EFileModel({
                        "name" : file.name,
                        "file" : file,
                    });

                    EFileUploader.collection.set([m]);

                };
                EFileUploader.render();

                var newImportModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "UsersImportForm"
                });

                if ($(this.el).find("#step2Form")) {
                    Yii.app.widget("EForm", {
                        el: $("#step2Form"),
                        model: newImportModel,
                        onSuccess: function (model, response) {
                            Yii.app.navigate(response.redirect);
                        }
                    }, function (form) {
                        form.render();
                    });
                }

                $(that.el).find(".delete-row").click(function() {
                    $(this).parents("tr").remove();
                });


            }

        })

    })
})