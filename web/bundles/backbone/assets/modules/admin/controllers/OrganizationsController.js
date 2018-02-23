$(function() {
    AdminModule.OrganizationsController = BaseController.extend({

        actionAdd : BaseAction.extend({

            _initialize : function() {

                var model = new BaseModel(this.controller.model.get("model"), {
                    yModel: "OrganizationsForm"
                });

                var orgForm = new EForm({
                    el: $("#newOrganizationsForm"),
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
                        cropper: false,
                        video: false,
                        uploadFileContainer: $(this.el).find(".uploader").find(".uploaded-loader"),
                        uploadFileTemplate: "#attached_file_template",
                        allowedExtensions: [
                            'jpg','png'
                        ],
                        onSuccess: function (uploader) {
                            var response = uploader.model.get("response");
                            $(orgForm.el).find('.uploaded-photo').html(
                                '<img src="' + response.url + '">'
                            );

                            $(orgForm.el).find("input[name='logo']").val(uploader.model.get("response").url);
                        },
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

        })

    })
})