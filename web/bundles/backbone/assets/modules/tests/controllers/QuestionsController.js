$(function() {

    TestsModule.QuestionsController = BaseController.extend({

        actionIndex : BaseAction.extend({
            afterRender : function() {
                var that = this;
                var filter  = this.controller.model.get("filter");
                $(that.el).find(".find-input").keypress(function(e) {
                    if (e.keyCode == 13) {
                        $(that.el).find(".find-button").click();
                    }
                });
                $(that.el).find(".find-button").click(function() {
                    filter.search = $(that.el).find(".find-input").val();
                    filter.theme_id = $(that.el).find(".themes-select").val();
                    that.controller.model.get("GET").filter = filter;
                    Yii.app.navigate(Yii.app.createOrganizationUrl("/tests/questions/index", that.controller.model.get("GET")));
                });
            }
        }),

    })

})