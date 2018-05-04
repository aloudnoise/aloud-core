$(function() {

    EJsonEditor = BaseWidget.extend({

        options : {
            attribute : null
        },

        afterRender : function () {

            var that = this;

            var options = {
                mode: 'code',
                sortObjectKeys: false
            };
            var editor = new JSONEditor($(this.el)[0], options);

            // set json
            var json = this.model.get(this.options.attribute);
            json = (_.isObject(json) || _.isArray(json)) ? json : JSON.parse(json);
            editor.set(json);

            $(this.el).parents("form").submit(function() {

                var json = editor.get();
                console.log(json);
                $(that.el).next("input[name='"+that.options.attribute+"']").val(JSON.stringify(json));

            })

        }

    })

})