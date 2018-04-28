$(function () {

    EForm = BaseWidget.extend({

        defaultOptions: {
            validationIcons: false,
            uploader: null
        },
        formVariable: null,
        inputs: [],
        events: {
            "submit": "submitForm"
        },
        onSuccess: null,
        onError: null,
        method: "post",
        store_data : true,
        _initialize: function (args) {

            this.onSuccess = args.onSuccess;
            this.onError = args.onError;

            if ($(this.el).attr("save-data") == 0)  {
                this.store_data = false;
            }

            if (this.store_data) {
                var stored = window.sessionStorage.getItem(this.model.yModel);
                if (stored) {

                    data = JSON.parse(stored);
                    console.log(data);
                    if (data.id) {
                        console.log(this.model.get("id"));
                        if (this.model.get("id") != data.id) {
                            window.sessionStorage.setItem(this.model.yModel, '');
                            return;
                        }
                    } else {
                        if (this.model.get("id")) {
                            window.sessionStorage.setItem(this.model.yModel, '');
                            return;
                        }
                    }


                    this.model.set(JSON.parse(stored));
                }
            }

        },

        render: function () {

            var that = this;

            if ($(this.el).attr("method")) {
                this.method = $(this.el).attr("method");
            }

            _(this.model.attributes).each(function (attr, k) {

                // Создаем экземпляр инпута

                if ($(that.el).find(".form-group[attribute='" + k + "']").length) {
                    var i = new Input({
                        el: $(that.el).find(".form-group[attribute='" + k + "']"),
                        parent: that,
                        attribute: k,
                        model: that.model,
                        data: "model"
                    }).render();

                    that.inputs.push(i);
                }

            });

            $(that.el).find(".form-group[attribute]").each(function() {

                if (!that.model.attributes[$(this).attr("attribute")]) {
                    var i = new Input({
                        el: $(this),
                        parent: that,
                        attribute: $(this).attr("attribute"),
                        model: that.model,
                        data: "model"
                    }).render();

                    that.inputs.push(i);
                }

            });

            this.model.on("change", this.destroyPopover, this);

            return this;

        },
        submitForm: function (event) {

            var that = this;

            event.preventDefault();

            if (this.options.uploader) {
                if (!this.options.uploader.isFinished()) {
                    $(event.currentTarget).find("input[type='submit']").popover({
                        content: Yii.t("main", "Файл еще не загрузился на сервер. Пожалуйста, подождите окончания загрузки"),
                        trigger: "manual"
                    }).popover("show");
                    return false;
                }
            }

            var data = this.serialize();
            this.model.set('submitter', document.activeElement.name);

            if (this.method == "post") {
                this.model.setUrl($(this.el).attr("action"), true);
                Yii.app.loading(true);
                this.model.save(data, {
                    success: function (model, response, options) {
                        Yii.app.loading(false);
                        window.sessionStorage.setItem(that.model.yModel, '');
                        if (typeof that.onSuccess == "function") {
                            that.onSuccess(model, response, options);
                        }
                    },
                    error: function (model, xhr, response) {
                        Yii.app.loading(false);
                        that.model.callFormError(xhr);
                        var first = false;
                        _(that.inputs).each(function (i) {
                            if (!first && that.model.getError(i.attribute)) {
                                first = true;
                                var body = $(Yii.app.currentController.target == "modal" ? ".wrapper #controller_modal" : "html, body");
                                body.stop().animate({
                                    'scrollTop': $(i.el).offset().top - 100
                                }, 1000);
                            }
                            i.render();
                        });
                        if (typeof that.onError == "function") {
                            that.onError(model, xhr, response);
                        }
                    }
                })
            } else {
                Yii.app.navigate(Yii.app.createUrl($(this.el).attr("action"), data));
            }

            /*
             } else {

             $(event.currentTarget).find("input[type='submit']").popover({
             content : Yii.t("main","Сначало исправьте ошибки в заполнении"),
             trigger : "manual"
             }).popover("show");

             } */

            return false;

        },
        destroyPopover: function (m) {
            //$(this.el).find("input[type='submit']").popover("destroy");
        },
        serialize: function () {

            var that = this;

            // _(this.model.attributes).each(function(a, k) {
            //     if (a === null) {
            //         that.model.set(k, "");
            //     }
            // })

            $(that.el).find("textarea[textareatype='ckeditor']").each(function () {
                if ($(this).attr("cktype") == "full") {
                    $(this).val(CKEDITOR.instances[$(this).attr("id")].getData());
                } else {
                    // console.log($(this));
                    // console.log($(this).val(CKEDITOR.instances[$(this).attr("id")]));
                    // $(this).val($(this).next(".cke_editable_inline").html());
                    $(this).val(CKEDITOR.instances[$(this).attr("id")].getData());
                }
            })

            var data = Backbone.Syphon.serialize(this);
            this.model.set(data);
        }
    });

    Input = BaseItem.extend({
        attribute: null,
        _initialize: function (args) {
            var that = this;
            this.parent = args.parent;
            this.setModelEvent();
            $(this.el).find("input,select,textarea").change(function(event) {
                that.changeAttribute(event);
            });

        },

        render : function() {
            var that = this;
            var input = $(this.el).find("input[name='"+this.attribute+"'], select[name='"+this.attribute+"'], textarea[name='"+this.attribute+"']").not(":checkbox").not(":radio");
            if ($(this.el).find("input[name='"+this.attribute+"'], select[name='"+this.attribute+"'], textarea[name='"+this.attribute+"']").not(":checkbox").not(":radio").length && this.model.get(this.attribute)) {
                console.log(that.model.get(that.attribute));
                $(this.el).find("input, select, textarea").each(function() {
                    if (($(this).attr("fixed") === undefined || !$(this).val()) && that.model) {
                        $(this).val(that.model.get(that.attribute));
                    }
                });
            }
            $(this.el).removeClass("").removeClass('has-success');
            if (input) {
                $(input).removeClass("is-invalid").removeClass("is-valid");
            }
            $(this.el).find(".invalid-feedback").remove();

            var add_class = false;
            var add_class_input = false;
            var add_html = false;
            var errors = this.model.getError(this.attribute);
            var msgs = "";
            if (errors !== false) {
                add_class = "has-danger";
                add_class_input = "is-invalid";
                _(errors).each(function (e) {
                    msgs += "<p class='invalid-feedback'>" + e + "</p>";
                })

            } else if (this.model.getSuccess(this.attribute) !== false) {
                add_class = "has-success";
                add_class_input = "is-valid";
            }

            if (add_class) {
                $(this.el).addClass(add_class);
            }
            if (add_class_input && input) {
                console.log($(input));
                console.log(add_class_input);
                $(input).addClass(add_class_input);
            }

            if (msgs != "") {
                $(this.el).append(msgs);
            }

            return this;

        },
        beforeInitialize: function (args) {
            this.attribute = args.attribute;
        },
        changeAttribute: function (event) {
            console.log('Changed !' + this.attribute);
            this.model.set(this.attribute, $(event.currentTarget).attr("value"));
            if (this.parent.store_data) {
                window.sessionStorage.setItem(this.model.yModel, JSON.stringify(this.model.toJSON()));
            }
        },
        setModelEvent: function () {
            // При изменении модели представления, полностью перерисовываем его
            this.model.on("change:" + this.attribute, this.validateAndRender, this);
        },
        validateAndRender: function (m) {
            this.model.validate();
            this.render();
        }
    })
});