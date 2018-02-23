$(function() {
    EConstructor = BaseWidget.extend({
        el : $(".constructor-questions-form"),
        form : null,
        questions : null,
        n : 1,
        _initialize: function (args) {

            var that = this;

            if (args.form) {
                this.form = args.form;
            }

            $(this.el).find(".questions-list").empty();

            if (!this.model.get("questionsJson")) {
                $(this.el).find(".no-questions").show();
            }

            this.questions = new BaseCollection();
            this.questions.on("add", this.newQuestion, this);
            this.questions.on("remove", function (m) {
                $(that.el).find(".selected-count").html(that.questions.length);
                if (that.questions.length === 0) {
                    $(that.el).find(".no-questions").show();
                    if (that.form) {
                        $(that.form.el).find("input[type='submit']").prop("disabled", true);
                    }
                }

                m.view.remove();
                that.reOrder();
            })

            if (args.questions) {
                this.questions.add(args.questions);
            }

            var addQuestionsForm = new this.addQuestionsForm({
                model: new BaseModel(),
                el: $(this.el),
                parent: this
            }).render();

        },

        addQuestionsForm: Backbone.View.extend({
            parent: null,
            activeEditor: null,
            initialize: function (args) {
                CKEDITOR.disableAutoInline = true;
                CKEDITOR.dtd.$removeEmpty['span'] = 0;
                this.parent = args.parent;
            },
            events: {
                "click .close-form": function () {
                    $(this.el).hide();
                },
                "click .add-question-confirm": "addQuestion",
                "click .add-imported-questions": "addImportedQuestions",
                "change .search-in-pool": function () {
                    this.searchPool(1);
                },
                "click .btn-import" : function() {

                    var that = this;
                    var importModel = new BaseModel({
                        "parse_questions" : $(".import-textarea").val(),
                        "type" : "text"
                    }, {
                        route : 'tests/import'
                    });

                    importModel.save({}, {
                        success : function(model, response, xhr) {
                            if (response.parsed) {
                                $(that.el).find(".import-textarea").val("");
                                that.parsedQuestions.add(response.parsed);
                                $(that.el).find(".add-imported-questions").show();
                            }
                        }
                    })

                }
            },
            formInputs: {},
            parsedQuestions: null,
            questionPool: null,
            pooln: 1,
            parsedn : 1,
            poolPager: null,
            render: function () {
                var that = this;


                this.formInputs['question'] = CKEDITOR.inline($(this.el).find('.add-question-textarea')[0], {
                    customConfig: 'config.js'
                });

                this.formInputs['answers'] = {};
                $(this.el).find(".add-answer-textarea").each(function () {
                    that.formInputs['answers'][$(this).attr("n")] = CKEDITOR.inline($(this)[0], {
                        customConfig: 'config.js'
                    });
                })

                this.parsedQuestions = new BaseCollection();
                this.parsedQuestions.on("add", this.addParsedQuestion, this);
                this.parsedQuestions.on("remove", function (m) {
                    $(that.el).find(".imported-count").html(that.parsedQuestions.length);
                    m.view.remove();
                });

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
                            that.convertDocument.call(that, uploader);
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



                this.pooln = 1;
                $(this.el).find(".questions-pool").empty();

                this.poolPager = new BaseModel({
                    page: 1
                });
                var poolPagerView = new BaseItem({
                    template: "#pool_pager_template",
                    model: this.poolPager,
                    events: {
                        "click .page:not('active')": function (event) {
                            $(that.el).parents(".modal").animate({
                                'scrollTop': $(that.el).parents(".modal").offset().top
                            }, 1000);
                            that.searchPool($(event.currentTarget).attr("page"));
                        }
                    }
                });
                this.poolPager.view = poolPagerView;
                $(this.el).find(".pool-pager").html($(poolPagerView.render().el));

                this.questionPool = new BaseCollection({}, {
                    pager: this.poolPager,
                    route : 'tests/questions',
                    model : BaseModel.extend({
                    })
                });

                this.questionPool.on("add", this.addInPool, this);
                this.questionPool.on("remove", function (m) {
                    if (m.view) {
                        m.view.remove();
                    }
                });

                $(this.el).find(".search-in-pool").keypress(function (e) {
                    if (e.charCode == 13) {
                        $(this).change();
                        return false;
                    }
                });
                $(this.el).find(".question-theme-id").change(function() {
                    $(that.el).find(".search-in-pool").change();
                });

                console.log('pooling');
                this.searchPool(1);

            },
            searchPool: function (p) {
                this.pooln = (p - 1) * 5 + 1;
                var data = {
                    page: p,
                    expand: 'answers'
                };
                if ($(this.el).find(".question-theme-id").val()) {
                    data.theme_id = $(this.el).find(".question-theme-id").val();
                }
                if ($(this.el).find(".search-in-pool").val()) {
                    data.name = $(this.el).find(".search-in-pool").val();
                }
                this.questionPool.fetch({
                    data : data
                })
            },
            addInPool: function (m) {
                m.set("n", this.pooln);
                m.set("question", {
                    name: m.get("name")
                })
                this.pooln++;

                var view = new this.poolQuestion({
                    model: m,
                    parent: this
                })
                m.view = view;
                $(this.el).find(".questions-pool").append($(view.render().el));
            },
            poolQuestion: BaseItem.extend({
                data: "item",
                template: "#pool_question_template",
                events: {
                    "click .add-question": "addQuestion",
                    "click .remove-question": "removeQuestion"
                },
                afterRender: function () {
                    $(this.el).find(".mjx").each(function () {
                        MathJax.Hub.Queue(
                            ["Typeset", MathJax.Hub, $(this)[0]]
                        );
                    })
                },
                addQuestion: function () {
                    this.parent.parent.questions.add(this.model.clone());
                    this.model.trigger("change");
                },
                removeQuestion: function () {
                    var s = this.parent.parent.questions.findWhere({
                        id: this.model.get("id")
                    })
                    this.parent.parent.questions.remove(s);
                    this.model.trigger("change");
                }
            }),
            addQuestion: function () {

                var that = this;
                var question = new BaseModel();
                question.set("name", that.formInputs['question'].getData());

                if ($(question.get("name")).html() == "") {
                    this.model.callError(Yii.t("main", "Текст вопроса не может быть пустым"));
                    return false;
                }

                var answers = [];
                _(this.formInputs['answers']).each(function (v, k) {
                    if (v.getData() != "") {
                        answers.push({
                            name: v.getData(),
                            is_correct: ($(that.el).find(".add-question-form tr[n='" + k + "'] input:checkbox").attr("checked") ? 1 : 0)
                        })
                    }
                });

                var correct = _(answers).findWhere({
                    is_correct: 1
                });

                if (!correct) {
                    question.callError(Yii.t("main", "Укажите хотябы 1 правильный ответ"));
                    return;
                }

                question.set("answers", answers);
                question.set("shared", 1);


                this.parent.questions.add(question);

                that.formInputs['question'].setData("");
                _(this.formInputs['answers']).each(function (v, k) {
                    $(that.el).find(".add-question-form tr[n='" + k + "'] input:checkbox").attr("checked", false);
                    v.setData("");
                });


            },
            convertDocument: function (uploader) {
                var that = this;

                var importModel = new BaseModel({
                    "parse_questions" : uploader.model.get("response").url,
                    "type" : "excel"
                }, {
                    route: 'tests/import'
                });

                importModel.save({}, {
                    success : function(model, response, xhr) {
                        if (response.parsed) {
                            console.log(response.parsed);
                            that.parsedQuestions.add(response.parsed);
                            $(that.el).find(".add-imported-questions").show();
                        }
                    }
                })

            },
            parseDocument: function (uploader, response) {

                var that = this;
                that.parsedQuestions.set({});

                var box = $(this.el).find(".uploaded-html");
                box.html(response);
                $(box).find("style").remove();
                $(box).find("script").remove();

                $(box).find('*:not(p,img)').each(function () {
                    if (!$(this).attr("face")) {
                        $(this).contents().unwrap();
                    } else {
                        $(this).replaceWith("<span style='font-family : " + $(this).attr("face") + ";'>" + $(this).html() + "</span>");
                    }
                });

                // Start parsing
                var current_question = null;
                var current_position = null;
                var variants = {
                    "A)": "0",
                    "B)": "2",
                    "C)": "3",
                    "D)": "4",
                    "E)": "5"
                };
                var push_p = function (pos, text) {

                    if (pos != "Q") {

                        if (typeof current_question['answers'] == "undefined") {
                            current_question['answers'] = {};
                        }

                        if (typeof current_question['answers'][pos] == "undefined") {
                            current_question['answers'][pos] = {};
                            current_question['answers'][pos]['n'] = pos;
                        }

                        current_question['answers'][pos]['name'] = (!_.isEmpty(current_question['answers'][pos]['name']) ? current_question['answers'][pos]['name'] : "") + text;

                    } else if (pos == "Q") {

                        current_question['name'] = (!_.isEmpty(current_question['name']) ? current_question['name'] : "") + text;

                    }
                };
                $(box).find("p").each(function () {

                    $(this).html($.trim($(this).html()));
                    var text = $(this).html();

                    if (parseInt(text.substring(0, 1)) > 0) {


                        if (current_question && typeof current_question['name'] != 'undefined' && typeof current_question['answers'] != 'undefined') {

                            that.parsedQuestions.add(current_question);

                            current_question = {};
                            current_position = "Q";

                        } else if (!current_question) {
                            current_question = {};
                            current_position = "Q";
                        }

                    }

                    if (typeof variants[text.substring(0, 2)] != "undefined") {
                        current_position = variants[text.substring(0, 2)];
                    }

                    if (text.length === 1 && _.values(variants).indexOf(text) !== -1) {
                        if (current_question) {

                            if (typeof current_question['answers'] != "undefined") {

                                if (typeof current_question['answers'][_.values(variants)[_.values(variants).indexOf(text)]] != "undefined") {
                                    current_question['answers'][_.values(variants)[_.values(variants).indexOf(text)]]['is_correct'] = 1;
                                    $(this).remove();
                                }


                            }

                        }
                    } else {
                        if (current_question && current_position) {
                            push_p(current_position, $(this).html());
                            $(this).remove();
                        }
                    }

                    if (!current_question) {
                        $(this).remove();
                    }

                })

                $(this.el).find(".add-imported-questions").show();
                Yii.app.loading(false);

            },
            addParsedQuestion: function (m) {

                var that = this;
                $(this.el).find(".imported-count").html(this.parsedQuestions.length);

                var question = m.get("name");
                if (question) {
                    m.set("use", 1);

                    /*var i = 1;
                    while (parseInt(question.substring(i - 1, 1)) > 0 && i < 5) {
                        i++;
                    }

                    m.set("n", parseInt(question.substring(0, i)));

                    question = $.trim(question.substring(i));
                    if (question.substring(0, 1) == ".") {
                        question = question.substring(1);
                    }

                    m.set("name", $.trim(question));

                    var answers = m.get("answers");
                    _(answers).each(function (v, k) {
                        answers[k]['name'] = answers[k]['name'].substring(2);
                    })
                    m.set("answers", answers);
                    */
                    m.set("n",that.parsedn)
                    var view = new this.parsedQuestion({
                        model: m,
                        parent: this
                    });

                    m.view = view;

                    $(this.el).find(".parsed-questions").append($(view.render().el));
                    that.parsedn++;
                }

            },
            parsedQuestion: BaseItem.extend({
                data: "item",
                template: "#parsed_question_template",
                events: {
                    "click .close-question": "deleteQuestion",
                    "click .correct-checkbox": function (event) {
                        var n = $(event.currentTarget).attr("n");
                        var answers = this.model.get("answers");
                        if (answers[n].is_correct == 1) {
                            answers[n].is_correct = 0;
                        } else {
                            answers[n].is_correct = 1;
                        }
                        this.model.set("answers", answers);
                        this.model.trigger("change");
                    },
                    "click .use-checkbox": function () {
                        if (this.model.get("use") == 1) {
                            this.model.set("use", 0);
                        } else {
                            this.model.set("use", 1);
                        }
                    }
                },
                afterRender: function () {
                    var that = this;
                    $(this.el).find(".editable").each(function () {

                        var t = $(this).attr("type");

                        /*
                        if (CKEDITOR.instances[$(this).attr("id")]) {
                            CKEDITOR.instances[$(this).attr("id")].destroy();
                        }
                        var e = CKEDITOR.inline($(this)[0], {
                            config: "inline_config.js"
                        });
                        e.on("change", function () {
                            if (t == "Q") {
                                that.model.set("name", e.getData(), {
                                    silent: true
                                })
                            } else {
                                var answers = that.model.get("answers");
                                _(answers).each(function (v, k) {
                                    if (v.n == t) {
                                        answers[k].name = e.getData();
                                    }
                                })
                                that.model.set("answers", answers, {
                                    silent: true
                                });
                            }
                        })
                        */
                    })
                },
                deleteQuestion: function () {
                    this.parent.questions.remove(this.model);
                }
            }),
            addImportedQuestions: function () {

                var that = this;

                if (!this.parsedQuestions) {
                    this.model.callError("Вы не загрузили ниодного вопроса");
                    return false;
                }

                var use_questions = this.parsedQuestions.where({
                    use: 1
                })

                if (use_questions.length > 0) {
                    var uncorrect = [];
                    _(use_questions).each(function (q) {
                        var correct = _(q.get("answers")).findWhere({
                            is_correct: 1
                        });
                        if (correct) {
                            that.parent.questions.add(q.clone());
                            that.parsedQuestions.remove(q);
                        } else {
                            uncorrect.push(q.get("n"));
                        }

                    })

                    if (uncorrect.length > 0) {

                        var msg = Yii.t("main", "Не указан правильный ответ в вопросах: ");
                        if (uncorrect.length === 1) {
                            msg = Yii.t("main", "Не указан правильный ответ в вопросе: ")
                            msg += uncorrect[0];
                        } else {
                            msg += uncorrect.join(",");
                        }

                        that.model.callError(msg);

                    }

                } else {
                    this.model.callError("Вы не выбрали ниодного вопроса");
                    return false;
                }

                $(that.el).find(".add-imported-questions").hide();

            }

        }),
        _an: 0,
        newQuestion: function (m) {

            var that = this;

            if (!that.model.get("questionsJson") || that.model.get("questionsJson").indexOf(m.get("id")) === -1) {
                this._an++;
                if (this._an === 1) {
                    setTimeout(function () {
                        var msg = "";
                        if (that._an === 1) {
                            msg = Yii.t("main", "Вопрос добавлен в список вопросов");
                        } else {
                            msg = Yii.t("main", "Вопросов добавлено в список вопросов: " + that._an);
                        }
                        m.callSuccess(msg);
                        that._an = 0;
                    }, 1000);
                }
            }

            $(this.el).find(".no-questions").hide();
            if (that.form) {
                $(that.form.el).find("input[type='submit']").prop("disabled", false);
            }

            $(this.el).find(".selected-count").html(this.questions.length);

            m.set("n", this.n);

            var view = new this.question({
                model: m,
                parent: this
            });

            m.view = view;

            this.n++;

            $(this.el).find(".questions-list").append($(view.render().el));

        },
        reOrder: function () {
            var that = this;
            this.n = 1;
            this.questions.each(function (q) {
                q.set("n", that.n);
                that.n++;
            })
        },
        question: BaseItem.extend({
            data: "item",
            template: "#question_template",
            events: {
                "click .close-question": "deleteQuestion"
            },
            afterRender: function () {
                $(this.el).find(".mjx").each(function () {
                    MathJax.Hub.Queue(
                        ["Typeset", MathJax.Hub, $(this)[0]]
                    );
                })
            },
            deleteQuestion: function () {
                this.parent.questions.remove(this.model);
            }
        })

    });
});