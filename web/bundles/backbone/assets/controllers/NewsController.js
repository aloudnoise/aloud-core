$(function() {
    NewsController = BaseController.extend({

        actionIndex : BaseAction.extend({
            afterRender : function()
            {

            }
        }),

        actionView : BaseAction.extend({
           afterRender : function() {

           }
        }),

        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;

                var newModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "News"
                });
                newModel.setRules(this.controller.model.get("rules"));
                newModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                CKEDITOR.replace($(that.el).find(".new-textarea")[0], {
                    customConfig: 'config.js'
                });

                Yii.app.widget("EForm", {
                    el : $("#newForm"),
                    model : newModel,
                    options : {
                        uploader : false
                    },
                    onSuccess : function(model,response) {
                        window.location.href = response.redirect;
                    }
                }, function(form) {
                    form.render();
                });

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
                            'jpg','png'
                        ],
                        onSuccess: function (uploader) {
                            var response = uploader.model.get("response");
                            $(that.el).find('.uploaded-photo').html(
                                '<img src="' + response.preview + '">'
                            );

                            $(that.el).find("input[name='image']").val(uploader.model.get("response").preview);
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

                $(that.el).find('input[name="tagsString"]').tagsinput({
                    typeaheadjs: {
                        source: function(q, sync) {
                            sync(that.controller.model.get("tags"));
                        }
                    }
                });

            }
        })
    })
});