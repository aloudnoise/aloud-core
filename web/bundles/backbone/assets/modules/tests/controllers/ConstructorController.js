$(function() {

    TestsModule.ConstructorController = BaseController.extend({

        actionCompile : BaseAction.extend({

            _initialize: function() {

                var that = this;
                var instrumentModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "InstrumentForm"
                });

                this.panel = this.controller.model.get("panel");
                if (this.panel.instrument) {

                    var classes = {
                        'simple_question' : 'SimpleQuestion',
                        'from_questions_list' : 'FromQuestionsList',
                        'text_import' : 'TextImport',
                        'file_import' : 'FileImport',
                        'full_theme' : 'FullTheme'
                    };

                    if (classes[this.panel.instrument]) {
                        var instrumentForm = new this[classes[this.panel.instrument]]({
                            el : $(that.el).find(".instrument-form"),
                            model : instrumentModel,
                            parent : that
                        });
                        instrumentForm.render();
                    }

                }

                if (this.controller.model.get("editing_question") == true) {
                    var instrumentForm = new this.SimpleQuestion({
                        el : $(that.el).find(".instrument-form"),
                        model : instrumentModel,
                        parent : that
                    });
                    instrumentForm.render();
                }

                if (this.controller.model.get("editing_theme") == true) {
                    var instrumentForm = new this.FullTheme({
                        el : $(that.el).find(".instrument-form"),
                        model : instrumentModel,
                        parent : that
                    });
                    instrumentForm.render();
                }

                // $(that.el).find(".instruments-panel").sticky({
                //     topSpacing: 75
                // });

                if ($(that.el).find(".instrument-form").length) {
                    $("body, html").stop().animate({scrollTop: $(that.el).find(".instrument-form").offset().top - 95}, 200);
                }

            },

            SimpleQuestion: BaseItem.extend({
                afterInitialize: function() {
                    var that = this;
                    var form = new EForm({
                        el: $("#instrumentForm"),
                        model: this.model,
                        options: {
                            uploader: false
                        },
                        onSuccess: function (model, response) {
                            if (response.redirect) {
                                Yii.app.navigate(response.redirect, null, {
                                    scroll : false
                                });
                            }
                        }
                    });
                    form.render();

                    $(that.el).find("textarea[textareatype='ckeditor']").each(function() {
                        CKEDITOR.inline($(this)[0], {
                            customConfig: 'config.js'
                        });
                    });


                    var reIndexAnswers = function () {
                        var i = 1;
                        $(that.el).find(".answer").each(function () {
                            $(this).attr("i", i);
                            $(this).find(".answer-correct").attr("name", "answers[" + i + "][is_correct]");
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
                        $(new_variant).find(".cke_textarea_inline").remove();
                        $(this).parents(".answer").after(new_variant);
                        CKEDITOR.inline($(new_variant).find("textarea[textareatype='ckeditor']")[0], {
                            customConfig: 'config.js'
                        });
                        reIndexAnswers();
                    });

                    $(that.el).find(".chosen-select").chosen();

                }
            }),

            FromQuestionsList: BaseItem.extend({
                afterInitialize: function () {
                    var that = this;
                    var filter = {};
                    if (this.parent.controller.model.get("GET").filter) {
                        filter = this.parent.controller.model.get("GET").filter;
                    }
                    $(that.el).find(".find-input").keypress(function (e) {
                        if (e.keyCode == 13) {
                            $(that.el).find(".find-button").click();
                        }
                    });
                    $(that.el).find(".find-button").click(function () {
                        filter.search = $(that.el).find(".find-input").val();
                        filter.theme_id = $(that.el).find(".themes-select").val();
                        that.parent.controller.model.get("GET").filter = filter;
                        Yii.app.navigate(Yii.app.createOrganizationUrl("/tests/constructor/compile", that.parent.controller.model.get("GET")));
                    });

                    $(that.el).find(".chosen-select").chosen();
                }
            }),

            TextImport: BaseItem.extend({
                afterInitialize: function () {
                    var that = this;

                    var form = new EForm({
                        el: $("#instrumentForm"),
                        model: this.model,
                        options: {
                            uploader: false
                        },
                        onSuccess: function (model, response) {
                            if (response.redirect) {
                                Yii.app.navigate(response.redirect);
                            }

                            if (response.parsed) {
                                $(that.el).find(".parsed-input").val(JSON.stringify(response.parsed));
                                $(that.el).find(".parsed-alert").find("count").html(response.count);
                                $(that.el).find(".import-block").hide();
                                $(that.el).find(".import-block").find("textarea").val('');
                                $(that.el).find(".parsed-block").show();
                                $(that.el).find(".chosen-select").chosen();

                                if (response.failed) {
                                    $(that.el).find(".parse-failed").show();
                                    $(that.el).find(".failed-text").html(response.failed);
                                }

                            }

                        }
                    });
                    form.render();



                },
            }),

            FileImport : BaseItem.extend({

                afterInitialize: function() {

                    var that = this;
                    console.log('x');

                    var EFileUploader = new EUploader({
                        el: $(this.el).find(".import-uploader-block"),
                        options: {
                            multiple: false,
                            cropper: false,
                            video: false,
                            allowedExtensions: [
                                'xls','xlsx'
                            ],
                            uploadFileTemplate: "#document_template",
                            onSuccess: function (uploader) {
                                $(that.el).find("input[name='document']").val(uploader.model.get("response").url);
                            }
                        }
                    });
                    EFileUploader.newFile = function (file) {
                        var m = new EFileModel({
                            "name": file.name,
                            "file": file
                        })

                        EFileUploader.collection.set([m]);

                    };
                    EFileUploader.render();

                    var form = new EForm({
                        el: $("#instrumentForm"),
                        model: this.model,
                        options: {
                            uploader: EFileUploader
                        },
                        onSuccess: function (model, response) {
                            if (response.redirect) {
                                Yii.app.navigate(response.redirect);
                            }

                            if (response.parsed) {
                                $(that.el).find(".parsed-input").val(JSON.stringify(response.parsed));
                                $(that.el).find(".parsed-alert").find("count").html(response.count);
                                $(that.el).find(".import-block").hide();
                                $(that.el).find(".import-block").find("textarea").val('');
                                $(that.el).find(".parsed-block").show();
                                $(that.el).find(".chosen-select").chosen();

                                if (response.failed) {
                                    $(that.el).find(".parse-failed").show();
                                    $(that.el).find(".failed-text").html(response.failed);
                                }

                            }

                        }
                    });
                    form.render();

                }

            }),

            FullTheme: BaseItem.extend({
                afterInitialize: function() {
                    var that = this;
                    var form = new EForm({
                        el: $("#instrumentForm"),
                        model: this.model,
                        options: {
                            uploader: false
                        },
                        onSuccess: function (model, response) {
                            if (response.redirect) {
                                Yii.app.navigate(response.redirect, null, {
                                    scroll : false
                                });
                            }
                        }
                    });
                    form.render();

                    $(that.el).find(".chosen-select").chosen();

                }
            })

        })

    })

})