$(function() {

    ELiveField = BaseWidget.extend({
        el: null,
        model : null,

        afterRender: function() {
            var that = this;

            $(that.el).width($(that.el).find(".list-field").width());
            $(that.el).find(".form-control").width($(that.el).find(".list-field").width());

            $(that.el).find(".list-field").click(function() {
                $(this).hide();
                $(this).parent().find(".form-control").show().focus();
            });

            $(that.el).find(".form-control").blur(function() {
                $(this).hide();
                $(this).parent().find(".list-field").show();
            });

            $(that.el).find(".form-control").change(function() {

                var el = this;
                that.model.set($(this).attr("name"), $(this).val());
                that.model.save({}, {
                    success : function() {
                        if ($(el)[0].tagName == "SELECT") {
                            $(el).parent().find(".list-field .list-field-value").html($(el).parent().find(".form-control option[value='"+$(el).val()+"']").html());
                        } else {
                            $(el).parent().find(".list-field .list-field-value").html($(el).val());
                        }
                        that.model.callSuccess(Yii.t("main","Поле успешно сохранено"));
                    },
                    error : function(m, response) {
                        m.callError(response);
                    }
                })

            })

        }
    })

})