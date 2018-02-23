$(function() {

    $.fn.extend({
        dynamicChosen: function(options) {

            var select = $(this);

            options = options || {};
            options = _.extend(options, {
                no_results_text: Yii.t("main", "Ничего не найдено, чтобы добавить элемент в список нажмите Enter")
            });

            $(this).chosen(options);
            var chosen = $(this).parent().find('.chosen-container');
            chosen.find('input').keyup( function(e)
            {
                // if we hit Enter and the results list is empty (no matches) add the option
                if (e.which === 13 && chosen.find('li.no-results').length > 0)
                {
                    var option = $("<option>").val("add#" + this.value).text(this.value);

                    // add the new option
                    select.prepend(option);
                    // automatically select it
                    select.find(option).prop('selected', true);
                    // trigger the update
                    select.trigger("chosen:updated");
                }
            });

        }
    });

})