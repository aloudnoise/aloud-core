$(function () {

    // Переопределяем базовый контроллер
    ExampleController = BaseController.extend({

        // Переопределяем базовый екшн
        actionIndex: BaseAction.extend({

            // Можно использовать метод _initialize, можно beforeRender
            _initialize: function() {

                // это для того чтобы обращатся к объекту экшна внутри анонимных функций с другой областью видимости this
                var that = this;

                // Тут пихаем основную логику js
                // ЧТобы получить доступ к вьюхе
                $(this.el); // <div class='action-content'></div>

                // Чтобы вытащить переменные из пхп контроллера переданные в \Yii::$app->data
                var model = this.controller.model.get("model"); // Example

                // пихнем эти данные в колонку во вьюхе
                $(this.el).find(".column-info-content").html("one_attr: " + model.one_attr + ", two_attr: " + model.two_attr + ", three_attr: " + model.three_attr);

                // ЧТобы добавить связь модель-вьюха на бекбоне
                var backboneModel = new BaseModel();
                var Item = BaseItem.extend({
                    template: "#column_info_template",
                    data: "item"
                });
                var view = new Item({
                    model : backboneModel
                });
                $(this.el).find('.column-info-content').html($(view.render().el));


                // Добавляем изменение модели каждые 2 секунды для наглядности
                $(this.el).everyTime(2000, 'x', function() {
                    // внутри this уже ссылается не на actionIndex объект, а на дивку action-content
                    // поэтому тут чтобы обратится к экшну используем that;
                    backboneModel.set({
                        one_attr : Math.random(2000),
                        two_attr : Math.random(10000),
                        three_attr : Math.random(50000)
                    });

                })


            }

        }),

        actionAdd: BaseAction.extend({

            _initialize: function() {

                var that = this;

                var model = new BaseModel(this.controller.model.get("model"), {
                    yModel: "Example"
                });

                var form = new EForm({
                    el: $("#exampleForm"),
                    model: model,
                    onSuccess: function (model, response) {

                        if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                            return;
                        }

                        if (response.redirect) {
                            Yii.app.navigate(response.redirect);
                        }
                    }
                }).render();

            }

        })

    })

});