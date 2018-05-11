$(function() {

    EJsonEditor = BaseWidget.extend({

        afterRender : function () {

            var that = this;

            var editors = [];

            $(".json-editor").each(function() {

                var el = $(this);
                var input = $(this).next("input[name='"+$(this).attr("id")+"']");
                var editor = null;

                var options = {
                    mode: 'code',
                    sortObjectKeys: false,
                    onChange: function() {
                        var json = editor.get();
                        $(input).val(JSON.stringify(json));
                    }
                };

                editor = new JSONEditor($(el)[0], options);

                // set json
                var json = $(input).val();
                json = JSON.parse(json);
                editor.set(json);

                editors.push(editor);

            });

        }

    })

})