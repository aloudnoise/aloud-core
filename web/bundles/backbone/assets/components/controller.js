$(function() {

    /**
     * Базовый класс контроллера
     * @type {*|void|extend|extend|extend|extend}
     */
    BaseController = BaseComponent.extend({
        // Название контроллера
        name : null,
        // Загружен ли контроллера (пришел не через аякс)
        loaded : false,
        // Текущий урл контроллера
        url : null,
        // Базовый урл ( в случае модального окна )
        baseUrl : null,
        // Модель контроллера
        model : null,
        // Класс базовой модели контроллера
        modelClass : "BaseModel",

        action : null,

        target : null,

        template : null,

        noState : false,

        sync : BaseSync,

        scrollTop : false,

        external : false,

        options : {},

        /**
         * Инициализация контроллера
         * @param args
         * @returns {boolean}
         */
        _initialize : function(args) {

            if (typeof args.url != 'undefined') {
                this.url = args.url;
            } else {
                $.jGrowl("Set controller url", {
                    sticky:false,
                    theme:'error',
                    life:6000
                });
                //return false; //asd
            }

            this.app = args.app;

            if (typeof args.baseUrl != 'undefined') {
                this.baseUrl = args.baseUrl;
            }

            if (typeof args.external != 'undefined') {
                this.external = args.external;
            }

            if (typeof args.noState != 'undefined') {
                this.noState = args.noState;
            }

            if (typeof args.target != 'undefined') {
                this.target = args.target;
            }

            if (typeof args.name != 'undefined') {
                this.name = args.name;
            } else {
                $.jGrowl("Set controller name", {
                    sticky:false,
                    theme:'error',
                    life:6000
                });
                //return false;
            }
            if (typeof args.loaded != 'undefined') {
                this.loaded = args.loaded;
            }

            if (args.template) {
                if ($(args.template).length) {
                    this.template = _.template($(args.template).html());
                }
            }

            if (args.options) {
                this.options = args.options;
            }

            this.initializeModel();

        },


        /**
         * Рендеринг контроллера, регистрируются скрипты, определяется действие
         * действие определяется на основании аттрибута модели (action) и собирается
         * из слов "action" + model.action с большой буквы ( пр : actionIndex )
         */
        _render : function(response) {

            var that = this;

            this.loadModel(response.model);

            if (_.isEmpty(this.model.get("GET"))) {
                this.model.set("GET", {});
            }

            if (this.loaded) {
                this.setElement($(Yii.app.el));
            } else {
                // Если указан шаблон контроллера, а не элемент, то вызываем шаблон и заполняем данными
                if (this.template) {

                    var html = this.template({
                        html: response.html,
                        data: this.model.toJSON(),
                        options: this.options
                    });

                    $(response.html).filter('script[type!="text/template"]').each(function () {
                        $.globalEval(this.text || this.textContent || this.innerHTML || '');
                    });

                    // Устанавливаем элемент
                    this.setElement($(html));

                    // Если контроллер загружается в модальном окне, по добавляем в дом, если нет, то заменяем
                    if (this.target == "modal") {
                        $(Yii.app.el).append($(this.el));
                    } else {
                        var cls = $(this.el).attr("class");
                        cls = cls.split(" ");
                        var selector = '';
                        _(cls).each(function(c) {
                            selector += '.' + c;
                        });
                        $(Yii.app.el).find(selector).replaceWith($(this.el));
                    }
                } else {
                    // Если же указан элемент, то заполняем его вернутым хтмлом
                    $(this.el).html(response.html);
                }
                // Вызываем метод после загрузки
                this.afterLoad(response);
            }

            this.getFlash();
            this.loadAction();

            Yii.app.trigger("controllerRendered");

            //Initialization
            Waves.attach('.wave-init, .left-menu .menu-item, button.btn, a.btn, div.btn', ['waves-block']);
            Waves.init();

            $(this.el).find(".slim-scroll").each(function() {
                var $el = $(this);
                var height = $el.height();
                $el.slimscroll({
                    height: height + 'px',
                    color: 'rgba(0,0,0,0.5)',
                    size: '6px',
                    alwaysVisible: false,
                    borderRadius: '0',
                    railBorderRadius: '0',
                    start : $el.attr("start") ? $el.attr("start") : "top"
                });
            });

        },

        loadAction: function() {
            var that = this;
            // Получаем действие контроллера из модели
            var a = that.model.get("action");
            if (a) {

                // Составляем строку метода действия типа actionIndex
                var a_words = a.split("-");
                if (a_words.length > 1) {
                    a = "";
                    _(a_words).each(function(w) {
                        a = a + w.charAt(0).toUpperCase() + w.slice(1);
                    })
                }
                var action = "action" + a.charAt(0).toUpperCase() + a.slice(1);

                // Проверяем на наличие обьекта представления действия
                if (typeof that[action] != "undefined") {

                    // Проверяем если в модели указан элемент действия для представления
                    var ael = that.model.get("action_element");
                    if (!ael)
                    {
                        ael = ".action-element#action_" + that.model.get("controller") + "_" + a;
                    }

                    if (!$(that.el).find(ael).length)
                    {
                        throw "No action element present '" + ael + "' in DOM";
                    }

                    var f = [
                        "function (){ return parent.apply(this, arguments); } ",
                        "function () { return parent.apply(this, arguments); }",
                        "function(){return c.apply(this,arguments)}",
                        "function (){ return parent.apply(this, arguments); }",
                        "function (){return c.apply(this,arguments)}",
                        "function () {return c.apply(this,arguments);}",
                        "function(){ return parent.apply(this, arguments); }"
                    ]

                    if (f.indexOf(that[action].toString(-1)) !== -1){
                        // Создаем экземпляр представления действия
                        that.action = new that[action]({
                            el : $(that.el).find(ael),
                            controller : that
                        });
                        that.action.render();
                        that.action.ga();
                    } else {
                        that[action].call(that,$(that.el).find(ael));
                        that.ga();
                    }

                } else {

                    // Если ни класса, ни метода нет, вызываем стандартный метод контроллера _action
                    that._action(action);
                    that.ga();
                }

            }
            //$(that.el).addClass("fadein");

            // Если контроллер запущен в модальном окне
            if (that.target == "modal") {
                // Показываем модальное окно
                $(that.el).modal("show");

                if (that.options.transaction) {
                    $(this.el).on("hidden.bs.modal", function () {
                        Yii.app.removeModal();
                        if (!that.navigating) {
                            that.target = null;
                            Yii.app.navigate(that.baseUrl, null, {
                                scroll: false
                            });
                        }
                    })
                }

            } else {
                // Выставляем титл страницы
                if (!that.external) {
                    document.title = that.model.get("pageTitle");
                }
                if (that.scrollTop) {
                    $(window).scrollTop(0);
                }  else {
                    if (Yii.app.top > 0) {
                        $(window).scrollTop(Yii.app.top);
                    }
                    Yii.app.top = 0;
                }

            }

        },

        /**
         * Базовый метод действия
         * @param action
         * @private
         */
        _action : function(action) {

        },
        /**
         * Если действие не обьект, то выполняем аналитику с контроллера
         */
        ga : function() {
            if (typeof ga != "undefined") {
                ga('send', {
                    'hitType': 'pageview',
                    'page': this.url
                });
            }
        },
        /**
         * Инициализирует модель, если контроллер был загружен изначально (не аяксом)
         */
        initializeModel : function() {
            this.model = new window[this.modelClass](this.model);
            this.model.on("change", this.pushState, this);
        },
        /**
         * Инициализирует модель после загрузки контроллера через аякс
         * @param model
         */
        loadModel : function(model) {
            this.model = new window[this.modelClass](model);
        },

        /**
         * Выводит сообщения зарегистрированные в \Yii::$app->session->setFlash()
         */
        getFlash : function() {
            var flash = this.model.get("flash");
            if (flash) {

                _(flash).each(function(f,k) {
                    $.jGrowl(f, {
                        sticky:false,
                        theme:k,
                        life:6000
                    });
                })
            }
        },
        /**
         * Показать хлебные крошки
         */
        breadcrumbs : function() {
            if (this.target != 'modal') {
                Yii.app.breadcrumbs(this.model.get("breadCrumbs"));
            }
        },
        /**
         * Выполняется после загрузки контроллера
         * @param response - результат аякс запроса
         */
        afterLoad : function(response) {
        },
        scroll : function(id) {
            $(document.body).animate({
                'scrollTop':   $("#"+id).offset().top - 100
            }, 300);
        },
        __destroy: function() {
            if (this.action !== null && typeof this.action != 'function') {
                this.action.__destroy();
            }
            delete this.model;
            delete this.action;
            if (Yii.app.socket) {
                Yii.app.socket.clear();
            }
        }
    })

})
