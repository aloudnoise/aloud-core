$(function() {
    DicsController = BaseController.extend({
        actionList : BaseAction.extend({

            _initialize : function()
            {

                var that = this;
                $(that.el).find(".find-input").keypress(function(e) {
                    if (e.keyCode == 13) {
                        $(that.el).find(".find-button").click();
                    }
                })
                $(that.el).find(".find-button").click(function() {
                    filter = {};
                    filter.s = $(that.el).find(".find-input").val();
                    Yii.app.navigate(Yii.app.createUrl("/dics/index", {"filter" : filter}));
                });

            }

        }),

        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;
                var newDicvModel = new BaseModel(this.controller.model.get("dicv"),{
                    yModel : "DicValues"
                });
                newDicvModel.setRules(this.controller.model.get("rules"));
                newDicvModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                Yii.app.widget("EForm", {
                    el : $("#newDicsvForm"),
                    model : newDicvModel,
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            Yii.app.navigate(response.redirect);
                        } else if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                        }
                    }
                }, function(form) {
                    form.render();
                });

                $("input.autocomplete").each(function() {
                    $(this).autocomplete({
                        serviceUrl: Yii.app.createOrganizationUrl('/dics/autocomplete'),
                        minChars: 1,
                        maxHeight: 400,
                        width: 300,
                        zIndex: 9999,
                        deferRequestBy: 200,
                        params: { attribute : $(this).attr("autocomplete-attribute"), dic_id: that.controller.model.get("dic").id},
                        onSelect: function(data, value){
                            $(that.el).find("input[name='parent_id']").val(data.data);
                        }
                    })
                });

            }
        })
    })
})