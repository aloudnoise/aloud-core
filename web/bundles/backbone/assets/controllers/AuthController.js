$(function() {
    AuthController = BaseController.extend({

        actionLogin : BaseAction.extend({
            _initialize: function () {
                var loginModel = new BaseModel(this.controller.model.get("user"), {
                    yModel: "LoginForm"
                });
                loginModel.setRules(this.controller.model.get("rules"));
                loginModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var loginForm = new EForm({
                    el: $("#loginForm"),
                    model: loginModel,
                    onSuccess: function (model, response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }
                }).render();

            }
        }),

        actionRegistration : BaseAction.extend({
            _initialize: function () {
                var model = new BaseModel(this.controller.model.get("model"), {
                    yModel: "RegistrationForm"
                });

                var registrationForm = new EForm({
                    el: $("#registrationForm"),
                    model: model,
                    onSuccess: function (model, response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }
                }).render();

                $(this.el).find("input[name='phone']").mask("+9(999)9999999");

            }
        }),

        actionRestore : BaseAction.extend({
            _initialize: function () {
                var model = new BaseModel(this.controller.model.get("model"), {
                    yModel: "RestoreForm"
                });
                model.setRules(this.controller.model.get("rules"));
                model.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var restoreForm = new EForm({
                    el: $("#restoreForm"),
                    model: model,
                    onSuccess: function (model, response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    },

                    onError: function (model, response) {
                        var j = JSON.parse(response.responseText);
                        if (j.html) {
                            $('.error-form')
                                .addClass('alert alert-danger')
                                .html(j.html);
                        }
                    }
                }).render();
            }
        }),

        actionRecovery : BaseAction.extend({
            _initialize: function () {
                var model = new BaseModel(this.controller.model.get("model"), {
                    yModel: "RestoreForm"
                });
                model.setRules(this.controller.model.get("rules"));
                model.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var recoveryform = new EForm({
                    el: $("#recoveryForm"),
                    model: model,
                    onSuccess: function (model, response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    },
                    onError: function (model, response) {
                        var j = JSON.parse(response.responseText);
                        if (j.html) {
                            $('.error-form')
                                .addClass('alert alert-danger')
                                .html(j.html);
                        }
                    }
                }).render();

            }
        })

    })

})