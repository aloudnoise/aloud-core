$(function() {

    EFilter  = BaseWidget.extend({

        defaultOptions: {
            route: null,
        },
        afterInitialize : function() {

            var that = this;

            if (!that.parent) {
                that.parent = Yii.app;
                that.parent.controller = that.parent.currentController;
            }

            if (!this.model) {
                this.model = new BaseModel();
            }

            this.inputs = $(this.el).find("*[data-role='filter'][data-action='input']");
            this.submits = $(this.el).find("*[data-role='filter'][data-action='submit']");
            this.resets = $(this.el).find("*[data-role='filter'][data-action='reset']");

            $(this.inputs).keypress(function(e) {
                if (e.keyCode == 13) {
                    that.submit();
                } else if ($(this).attr("data-live") == "1") {
                    $(this).stopTime('livepress');
                    $(this).oneTime(700, 'livepress', function() {

                        var el = this;
                        that.submit(function(controller) {

                            var input = $(controller.el).find('input[name="'+$(el).attr("name")+'"]');
                            var tmpStr = input.val();
                            input.focus();
                            input.val('');
                            input.val(tmpStr);

                        });

                    })
                }
            });

            $(this.inputs).change(function(e) {
                if ($(this).attr("data-live") == "1") {
                    that.submit();
                }
            });

            $(this.resets).click(function(e) {
                e.stopPropagation();
                e.preventDefault();
                that.reset();
                return false;
            });

            $(this.submits).click(function(e) {
                e.stopPropagation();
                e.preventDefault();
                that.submit();
                return false;
            });

        },

        submit: function(callback) {

            var that = this;

            $(this.inputs).each(function() {
                if ($(this).attr("type") == "checkbox") {
                    var n = $(this).attr("name").split("[");
                    if (n.length > 1) {

                        var arr = that.model.get(n[0]);
                        var l = n[1].split("]");
                        if (l[0] != '') {

                            if (!arr) {
                                arr = {};
                            }
                            if ($(this).prop("checked")) {
                                arr[l[0]] = $(this).attr("value");
                            } else {
                                arr[l[0]] =  null;
                            }
                        } else {
                            if (!arr) {
                                arr = [];
                            }

                            if ($(this).prop("checked")) {
                                if (arr.indexOf($(this).attr("value")) === -1) {
                                    arr.push($(this).attr("value"));
                                }
                            } else {
                                if (arr.indexOf($(this).attr("value")) !== -1) {
                                    arr.splice(arr.indexOf($(this).attr("value")), 1);
                                }
                            }

                        }
                        that.model.set(n[0], arr);
                    } else {
                        if ($(this).prop("checked")) {
                            that.model.set($(this).attr("name"), $(this).attr("value"));
                        } else {
                            that.model.set($(this).attr("name"), null);
                        }
                    }

                } else {

                    var n = $(this).attr("name").split("[");
                    if (n.length > 1) {

                        var arr = that.model.get(n[0]);

                        var l = n[1].split("]");
                        if (l[0] != '') {

                            if (!arr) {
                                arr = {};
                            }

                            arr[l[0]] = $(this).attr("value");

                        } else {

                            if (!arr) {
                                arr = [];
                            }

                            if (arr.indexOf($(this).attr("value")) !== -1) {
                                arr.splice(arr.indexOf($(this).attr("value")), 1);
                            }

                            arr.push($(this).attr("value"));

                        }

                        that.model.set(n[0], arr);

                    } else {
                        that.model.set($(this).attr("name"), $(this).val());
                    }
                }
            });

            var get = that.parent.controller.model.get("GET");
            get.filter = that.model.attributes;

            if (callback) {
                if (that.parent.controller.options.callback) {
                    callback = function() {
                        that.parent.controller.options.callback.apply(that.parent);
                        callback.apply(that.parent);
                    }
                }
            }

            that.parent.controller.navigate(Yii.app.createOrganizationUrl(that.options.route, get), that.parent.controller.target, {
                scroll: false,
                callback: callback ? callback : (that.parent.controller.options.callback ? that.parent.controller.options.callback : null)
            });

        },

        reset: function() {

            var that = this;

            var get = that.parent.controller.model.get("GET");
            get.filter = null;
            that.parent.controller.navigate(Yii.app.createOrganizationUrl(that.options.route, get), that.parent.controller.target, {
                scroll: false
            });

        }

    })

})