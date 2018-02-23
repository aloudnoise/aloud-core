$(function() {
    LibraryController = BaseController.extend({

        actionIndex : BaseAction.extend({

            afterRender : function() {

                var that = this;

                Yii.app.widget("EFilter", {
                    el : this.el,
                    parent: that,
                    model : new BaseModel(that.controller.model.get("GET").filter),
                    options : {
                        route : '/library/index'
                    }
                }, function(filter) {
                    filter.render();
                });

            }

        }),

        actionAdd : BaseAction.extend({

            _initialize : function(args) {

                var that = this;

                $(this.el).find(".chosen").chosen();

                var body = this.controller.model.get("model").infoJson;
                var newMaterialModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Materials"
                });

                var is_der = 0;
                if (newMaterialModel.get("type") == 4) {
                    is_der = 1;
                }

                if ($(this.el).find(".mtype-file .uploader-block").length) {
                    // Initiate Uploaders
                    var EFileUploader = new EUploader({
                        el: $(this.el).find(".mtype-file .uploader-block"),
                        options: {
                            multiple: false,
                            cropper: false,
                            video: false,
                            uploadFileTemplate: "#uploaded_file_template",
                            onSuccess: function (file) {
                                var response = file.model.get("response");
                                if (!is_der) {
                                    if (!newMaterialModel.get("name")) {
                                        var s = String(response.name).replace(/_/g, " ");
                                        newMaterialModel.set("name", s.split(".")[0]);
                                        $("#newMaterialForm").find("input[name='name']").val(s.split(".")[0]);
                                    }
                                } else {
                                    if (response.zip_content && response.zip_content.files && response.zip_content.files.indexOf("/tincan.xml") !== -1) {
                                        var x = new XMLHttpRequest();
                                        x.open("GET", response.zip_content.host + response.zip_content.files[response.zip_content.files.indexOf("/tincan.xml")], true);
                                        x.onreadystatechange = function () {
                                            if (x.readyState == 4 && x.status == 200) {
                                                var tincanxml = $(jQuery.parseXML(x.responseText));
                                                if (!newMaterialModel.get("name") && $(tincanxml).find("tincan").find("activities").first().find("name").length) {
                                                    var s = $(tincanxml).find("tincan").find("activities").first().find("name").html();
                                                    newMaterialModel.set("name", s);
                                                    $("#newMaterialForm").find("input[name='name']").val(s);
                                                }

                                                $(that.el).find("input[name='activity_id']").val($(tincanxml).find("tincan").find("activity").first().attr("id"));

                                                if (!newMaterialModel.get("description") && $(tincanxml).find("tincan").find("activities").first().find("description").length) {
                                                    var s = $(tincanxml).find("tincan").find("activities").first().find("description").html();
                                                    newMaterialModel.set("description", s);
                                                    $("#newMaterialForm").find("textarea[name='description']").val(s);
                                                }

                                            }
                                        };
                                        x.send(null);
                                    }
                                }

                                $(that.el).find(".file-input").val(JSON.stringify(response));

                            }
                        }
                    });
                    EFileUploader.newFile = function (file) {
                        var m = new EFileModel({
                            "name": file.name,
                            "file": file,
                            "unzip": is_der
                        })

                        EFileUploader.collection.set([m]);

                    };
                    EFileUploader.render();

                    if (body && body.file) {
                        var b = new EFileModel({
                            name: body.file.name,
                            response: body.file,
                            file: body.file,
                            finished: true
                        })

                        EFileUploader.collection.set([b])
                    }
                }

                if ($(this.el).find(".mtype-conference .uploader-block").length) {
                    // Initiate Uploaders
                    var EFileUploader = new EUploader({
                        el: $(this.el).find(".mtype-conference .uploader-block"),
                        options: {
                            multiple: false,
                            cropper: false,
                            video: false,
                            uploadFileTemplate: "#uploaded_file_template",
                            allowedExtensions: ['pdf'],
                            onSuccess: function (file) {
                                var response = file.model.get("response");
                                $(that.el).find(".file-input").val(JSON.stringify(response));
                            }
                        }
                    });
                    EFileUploader.newFile = function (file) {
                        var m = new EFileModel({
                            "name": file.name,
                            "file": file,
                            "unzip": 0
                        })

                        EFileUploader.collection.set([m]);

                    };
                    EFileUploader.render();

                    if (body && body.presentation) {
                        var b = new EFileModel({
                            name: body.presentation.name,
                            response: body.presentation,
                            file: body.presentation,
                            finished: true
                        });
                        EFileUploader.collection.set([b])
                    }
                }

                Yii.app.widget("EForm", {
                    el : $("#newMaterialForm"),
                    model : newMaterialModel,
                    options : {
                        uploader : EFileUploader ? EFileUploader : false
                    },
                    onSuccess : function(model,response) {
                        window.location.href = response.redirect;
                    }
                }, function(form) {
                    form.render();

                });


                if ($(that.el).find(".mtype-video .video-block").length) {
                    var EVideoLinker = new EUploader({
                        el: $(this.el).find(".mtype-video .video-block"),
                        options: {
                            multiple: false,
                            files: false,
                            video: true
                        }
                    });
                    EVideoLinker.render();

                    EVideoLinker.on("parsed_video", function () {

                        $(that.el).find(".video-value").val(JSON.stringify(EVideoLinker.video.toJSON()));

                        EVideoLinker.video.item.getMetaData(function (json) {
                            if (typeof json != "undefined") {
                                if (json.title) {
                                    if ($(that.el).find("input[name='name']").val() == "") {
                                        $(that.el).find("input[name='name']").val(json.title)
                                    }
                                }

                                if (json.author_name) {
                                    if ($(that.el).find("input[name='source']").val() == "") {
                                        $(that.el).find("input[name='source']").val(json.author_name)
                                    }
                                }
                            }
                        })
                    })
                }

                if (body && body.video && body.video.video_id) {

                    EVideoLinker.video = new BaseModel({
                        "type" : body.video.type,
                        "video_id" : body.video.video_id,
                        "width" : body.video.width,
                        "height" : body.video.height
                    });

                    var video_view = new EVideo({
                        model : EVideoLinker.video,
                        parent : EVideoLinker,
                        options : EVideoLinker.options
                    });

                    $(EVideoLinker.el).find(".video-preview").html($(video_view.render().el));

                }

                $(this.el).find("input[name='is_live']").change(function() {

                    if ($(this).prop("checked")) {
                        $(that.el).find(".live-block").show();
                    } else {
                        $(that.el).find(".live-block").hide();
                    }

                });

                $(this.el).find('.live-block input[name="live_date"]').datepicker({
                    isRTL: false,
                    format: 'dd.mm.yyyy',
                    autoclose:true,
                    language: that.controller.model.get("language"),
                    startDate: "NOW"
                });

                $(this.el).find('.live-block input[name="live_time"]').timepicker({
                    template: "dropdown",
                    minuteStep : 5,
                    snapToStep: true,
                    showSeconds: false,
                    showMeridian: false,
                    defaultTime: false,
                    appendWidgetTo: "body",
                    icons: {
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down'
                    }
                });
                $(this.el).find('.live-block input[name="live_time"]').focus(function () {
                    $(this).timepicker("showWidget");
                })

                $(that.el).find('input[name="tagsString"]').tagsinput({
                    typeaheadjs: {
                        source: function(q, sync) {
                            sync(that.controller.model.get("tags"));
                        }
                    }
                });

                // $("input.theme-autocomplete").autocomplete({
                //     serviceUrl: Yii.app.createOrganizationUrl('/dics/autocomplete'),
                //     minChars: 1,
                //     maxHeight: 400,
                //     width: 300,
                //     zIndex: 9999,
                //     deferRequestBy: 200,
                //     params: { dic : 'DicQuestionThemes'},
                // });

                $(that.el).find(".chosen-select").dynamicChosen();


            },
            afterRender : function() {
                $(this.el).find(".chosen-container").width("100%");
            }

        }),

        actionView : BaseAction.extend({
            afterRender : function() {

                var that= this;

                var material = this.controller.model.get("material");
                if (material.infoJson.video) {
                    var video = new EmbedVideo({
                        el : $("#"+material.infoJson.video.video_id),
                        model : new BaseModel(material.infoJson.video)
                    });
                    video.render();
                }

                if (material.type = 4) {
                    var derframe = $(that.el).find("iframe.der");
                    var unf = $(that.el).find("a.der-fullscreen-cancel");
                    var ratio = 1;
                    var parent_el = '.action-content';
                    if ($(that.el).parents(".modal").length) {
                        parent_el = '.modal';
                        ratio = 1.4;
                    }
                    $(that.el).find("iframe.der").height($(derframe).parents(parent_el).width()/(($(window).width()/$(window).height()*ratio)));
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
                        $(derframe).height($(that.el).find("iframe.der").parents(parent_el).width()/(($(window).width()/$(window).height()*ratio)));
                        $(this).hide();
                        $("body").css({overflow:"visible"});
                        $(".wrapper").css({marginTop:0});
                        $(window).scrollTop(Yii.app.top);
                    })
                }

                if (that.controller.model.get("result")) {

                    that.result = new BaseModel(that.controller.model.get("result"), {
                        route : 'library/process'
                    });

                    $(that.el).everyTime(10000, "process", function () {
                        that.result.save();
                    });

                }

            },
            _destroy: function() {
                $(this.el).stopTime("process");
            }
        }),


    })

})