$(function() {

    // Стартуем хистори
    Backbone.history.start({ pushState: true });

    // Кэширование запросов
    $.ajaxSetup({
        cache: true
    });

    /**
     * Логируем сообщения, если дебаг
     * @param msg
     */

    if (!CORE.DEBUG) {
        console = {};
        console.log = function(){};
    }

    log = function(msg) {
        if (CORE.DEBUG) {
            console.log(msg);
        }
    }

    /**
     * Главный класс приложения
     * @type {*}
     */
    BaseApplication = Backbone.View.extend({
        el : "body",
        currentModule : null,
        // Текущий загруженный контроллер
        currentController : null,
        // Загруженные скрипты и стили
        assets : {
            css : [],
            js : [],
            jsScripts : {}
        },
        // Виджеты приложения
        widgets : {},
        templates : {},
        user : {},
        socket : null,
        stepsInModal : 0,
        top : 0,
        controllers : {},
        // Инициализация приложения
        initialize : function(args) {

            var that = this;

            // console.log('attach to base links');
            // $("body").on("click", "a[href^='/'],div[href^='/']", function(event) {
            //
            //     console.log('link clicked in base');
            //
            //     var link = $(event.currentTarget);
            //     var target = $(link).attr("target") ? $(link).attr("target") : null;
            //     var options = {
            //         scroll : $(link).attr("noscroll") ? false : true,
            //         confirm : $(link).attr("confirm") ? $(link).attr("confirm") : null
            //     };
            //
            //     return that.navigate($(link).attr("href"), target, options);
            // });

            this.controllers = new BaseCollection();
            this.controllers.on("add", function() {
                console.log('new_controller');
            });

            // Инициируем лонг поллинг
            this.socket = new Socket();
            this.socket.start();

            // Если меняется состояние текущего урла
            window.onpopstate = function(event) {
                that.returnState(event);
            };

            this.pushAssets();

        },

        render : function()
        {
            // Проверяем есть ли вызванный модал
            if (typeof $_GET['z'] != 'undefined') {
                this.navigate($_GET['z'], "modal", {
                    no_fade: true
                });
            }

            Yii.app.loading(false);

        },

        /**
         * Направляет по урлу через АЯКС
         * @param href - нажатая ссылка, либо урл
         * @param target
         * @param options
         * @returns {boolean}
         */
        navigate : function(href, target, options) {

            var that = this;

            _o = {
                scroll : true,
                transaction : true,
                callback: null,
                confirm : null
            };

            options = _.extend(_o, options);

            if (target === "_parent" || $("head base").attr("target") === "_parent") { return true; }
            if (target === "_full") {
                window.location.href = href;
                return false;
            }
            if (target === "_blank")
            {
                window.open(href, '_blank');
                return false;
            }

            if (options.confirm) {
                if (!confirm(options.confirm)) {
                    return false;
                }
            }

            if (!target && (Yii.app.currentController != null && Yii.app.currentController.target == "modal")) {
                options['scroll'] = false;
                target = "modal";
            } else if (target == "normal" && Yii.app.currentController != null && Yii.app.currentController.target == "modal") {
                Yii.app.currentController.navigating = true;
                $(Yii.app.currentController.el).modal("hide");
            }

            that.loadControllers(href, target, options);

            return false;
        },
        /**
         * Загружает скрипт контроллера и вызывает его
         * @param href
         * @param target
         * @param options
         */
        loadControllers : function(href, target, options) {

            Yii.app.loading(true);
            var that = this;
            // Вызываем аяксом данные по контроллеру
            $.ajax({
                type : "post",
                data : {
                    target : target
                },
                url : href,
                dataType : "json",
                success : function(response) {

                    // Выводим сообщения
                    // Если вернулось перенаправление, то перенаправляем
                    if (response.redirect) {
                        if (response.redirect) {
                            if (response.full) {
                                window.location.href = response.redirect;
                            } else {
                                Yii.app.navigate(response.redirect, response.target, {
                                    "scroll" : response.scroll ? response.scroll : false
                                });
                            }
                        }
                        return false;
                    }

                    if (response.refresh) {
                        if (response.full) {
                            window.location.href = window.location.href;
                        } else {
                            Yii.app.navigate(window.location.href);
                        }
                        return false;
                    }

                    options.href = href;

                    if (options.transaction) {
                        // Убиваем контроллеры
                        if (that.controllers) {
                            _(that.controllers).each(function (controller, c_id) {
                                if (controller) {
                                    _(controller).each(function (action, a_id) {
                                        that.controllers[c_id][a_id].__destroy();
                                        delete that.controllers[c_id][a_id];
                                    });
                                }
                            })
                        }
                    } else {
                        that.removeModal();
                    }

                    // Подключаем возвращенную модель
                    that.registerScripts(response.model, function () {
                        that.renderController(response, target, options);
                    });

                    Yii.app.loading(false);

                },
                error : function(response) {
                    Yii.app.loading(false);

                    try {
                        var r = JSON.parse(response.responseText);
                        if (r.message) {
                            $.jGrowl(JSON.parse(response.responseText).message, {
                                sticky: false,
                                theme: 'error',
                                life: 6000
                            });
                        } else {
                            window.location.href = this.url;
                        }
                    } catch(error) {
                        window.location.href = this.url;
                    }
                }
            });
        },
        renderController : function(response, target, options, external) {

            var that = this;
            var modules = response.model.modules ? response.model.modules : [];
            var controller = response.model.controller.charAt(0).toUpperCase() + response.model.controller.slice(1) + "Controller";

            var renderCurrentController = function(module_class) {
                var baseUrl = '';

                if (response.model.baseUrl) {
                    baseUrl = response.model.baseUrl;
                } else {
                    if (that.currentController && !external) {
                        if ((target == "modal" && that.currentController.target != "modal")) {
                            baseUrl = that.currentController.url;
                        } else if (that.currentController.target == "modal") {
                            baseUrl = that.currentController.baseUrl;
                            options.no_fade = true;
                        }
                    } else {
                        baseUrl = options.baseUrl;
                    }
                }

                var obj = null
                if (module_class) {
                    obj = window[module_class];
                } else {
                    obj = window;
                }

                var templates = {
                    'modal': '#controller_modal_template',
                }



                // Удаляем модальное окно, если текущий контроллер открыт в модальном окне
                var c = new obj[controller]({
                    template : templates[target] ? templates[target] : "#controller_template",
                    name : controller,
                    loaded : options.loaded,
                    url : options.href,
                    external: options.external,
                    baseUrl : baseUrl,
                    target : target,
                    options: options
                });

                if (module_class) {
                    c.module = module_class;
                }
                c.scrollTop = options.scroll;

                if (options.transaction) {
                    if (!external) {
                        if (that.currentController) {
                            that.currentController.__destroy();
                        }
                        delete that.currentController;
                        that.currentController = c;
                    } else {
                        if (that.controllers[controller] && that.controllers[controller][response.model.action]) {
                            that.controllers[controller][response.model.action].__destroy();
                            delete that.controllers[controller][response.model.action];
                        }
                        if (!that.controllers[controller]) {
                            that.controllers[controller] = {};
                        }
                        that.controllers[controller][response.model.action] = c;
                    }

                    c.render(response);

                    if (!external) {
                        if (!options.loaded && !options.no_push) {
                            that.pushState(c);
                        } else {
                            if (!c.noState) {
                                that.replaceState(c);
                            }
                        }

                        if (response.model.external) {
                            options.loaded = true;
                            options.external = true;
                            _(response.model.external).each(function (controller) {
                                _(controller).each(function (action) {
                                    that.registerScripts(action, function () {
                                        if (action.action) {
                                            that.renderController({
                                                model: action
                                            }, 'normal', options, true);
                                        }
                                    });
                                })
                            });
                        }

                    }
                } else {
                    c.render(response);
                }

            };

            var loadControllerScripts = function(module_class, module_path) {
                var obj = null;
                if (module_class) {
                    obj = window[module_class];
                } else {
                    obj = window;
                }
                if (obj && typeof obj[controller] == 'undefined') {
                    $.getScript(CORE.BACKBONE_CLIENT_ASSETS + (module_path ? (module_path) : "") + "/controllers/" + controller + ".js", function (data, textStatus, jqxhr) {
                        renderCurrentController(module_class);
                    }).fail(function (jqxhr, settings, exception) {
                        // $.jGrowl(exception.message, {
                        //     sticky: false,
                        //     theme: 'error',
                        //     life: 6000
                        // });
                        console.log('no controller file');
                    });
                } else {
                    renderCurrentController(module_class);
                }
            };

            var renderCurrentModule = function(module_class, module, module_path) {

                // Удаляем модальное окно, если текущий контроллер открыт в модальном окне
                var m = new window[module_class]({
                    name : module
                });

                if (!external) {
                    that.currentModule = m;
                }

                loadControllerScripts(module_class, module_path);

            };

            // Проверяем не загружен ли скрипт контроллера, если нет то подгружаем
            if (modules.length) {
                var module_path = "";
                var module_class = "";
                _(modules).each(function(module, key) {

                    module_path = module_path + '/modules/' + module;
                    if (key != modules.length - 1) {
                        module_class = module_class + module + "_";
                    }

                });

                var module = modules[modules.length - 1];
                var module_file_class = module.charAt(0).toUpperCase() + module.slice(1) + "Module";
                module_class = module_class + module_file_class;

                if (typeof window[module_class] == "undefined") {
                    $.getScript(CORE.BACKBONE_CLIENT_ASSETS + module_path + "/" + module_file_class + ".js", function (data, textStatus, jqxhr) {
                        renderCurrentModule(module_class, module, module_path);
                    }).fail(function (jqxhr, settings, exception) {
                        $.jGrowl(exception.message, {
                            sticky: false,
                            theme: 'error',
                            life: 6000
                        });
                    });
                } else {
                    renderCurrentModule(module_class, module, module_path);
                }

            } else {
                loadControllerScripts(null);
            }
        },

        removeModal : function() {
            if ($("#controller_modal").length) {
                $(".modal-backdrop").remove();
                $("#controller_modal").remove();
            }
        },

        /**
         *
         * TODO Доработать до нормального состояния
         *
         * Загружает яваскриптовый виджет
         * @param name
         */
        widget : function(name, args, callback) {
            var widget_path = CORE.BACKBONE_ASSETS + "/widgets/" + name + ".js";

            var returnWidget = function() {
                if (typeof(callback) == "function") {
                    if (window[name]) {
                        callback(new window[name](args));
                    }
                }
            }

            if (typeof window[name] != 'undefined') {
                return returnWidget();
            } else {
                this.loadScript(widget_path, function( data, textStatus, jqxhr )
                {
                    return returnWidget();
                });
            }
        },
        /**
         *
         * TODO Доработать до нормального состояния
         *
         * @param name
         * @param callback
         */
        loadTemplate : function(name, callback) {
            var that = this;
            var returnTemplate = function() {
                callback(that.templates[name]);
            }
            if (typeof this.templates[name] != "undefined")
            {
                returnTemplate();
            } else {
                $.get(name, function (data) {
                    that.templates[name] = data;
                    returnTemplate();
                });
            }
        },
        /**
         * Загружает скрипт, проверяет если не загружен
         * @param script
         * @param callback
         */
        loadScript : function(script, callback) {
            var that = this;

            var script_name = script;
            if (typeof script == "object") {
                script_name = script.id;
            }

            if (this.assets.js.indexOf(script_name) === -1 || (typeof script == "object" && script.force)) {
                if (typeof script == 'object') {
                    eval(script.script);
                    if (this.assets.js.indexOf(script_name) === -1) {
                        that.assets.js.push(script_name);
                    }
                    if (typeof callback == 'function') {
                        callback();
                    }
                } else {

                    $.getScript(script, function( data, textStatus, jqxhr ) {
                        that.assets.js.push(script);
                        if (typeof callback == 'function') {
                            callback( data, textStatus, jqxhr );
                        }
                    }).fail(function(jqxhr, settings, exception ) {
                        if (typeof callback == 'function') {
                            callback(jqxhr, settings, exception);
                        }
                        $.jGrowl(exception.message, {
                            sticky:false,
                            theme:'error',
                            life:6000
                        });
                    });
                }

            } else {
                if (typeof callback == 'function') {
                    callback();
                }
            }
        },
        /**
         * Регистрирует подключаемые скрипты и стили
         */
        registerScripts : function(model, callback) {
            var css = model.css;
            var js = model.js;

            if (css && css.length) {
                _(css).each(function(css_file) {
                    Yii.app.loadStyle(css_file);
                });
            }

            var loadSync = function(i) {
                if (typeof js[i] != 'undefined') {
                    Yii.app.loadScript(js[i], function() {
                        i++;
                        loadSync(i);
                    });
                } else {
                    if (typeof callback == "function") {
                        callback();
                    }
                }
            }

            if (js && js.length) {
                loadSync(0);
            } else {
                if (typeof callback == "function") {
                    callback();
                }
            }

        },
        /**
         * Загружает стили, если не загружен
         * @param style
         * @param callback
         */
        loadStyle : function(style, callback) {
            if (this.assets.css.indexOf(style) === -1) {
                $('<link/>', {
                    rel: 'stylesheet',
                    type: 'text/css',
                    href: style
                }).appendTo('head');
                this.assets.css.push(style);

            } else {

            }
            if (typeof callback == 'function') {
                callback()
            }
        },
        pushAssets : function() {

            var that = this;

            // Логим подключеные скрипты и стили, для последующей проверки, чтобы не подключать 2 раза одно и тоже
            $("html script[src!='']").each(function() {
                if ($(this).attr("src") != undefined) that.assets.js.push($(this).attr("src"));
            })
            $("html script[type='text/javascript'][id!='']").each(function() {
                if ($(this).attr("id") != undefined) {
                    that.assets.js.push($(this).attr("id"));
                    that.assets.jsScripts[$(this).attr("id")] = $(this).html();
                }

            })

            $("head link").each(function() {
                if ($(this).attr("href") != undefined) that.assets.css.push($(this).attr("href"));
            });
        },
        loading : function(load) {
            if (load) {
                $("body").find("#preloader").addClass('top').show();
                $("body").find("#preloader").find("#status").show();
            } else {
                $('#preloader').delay(250).fadeOut('slow', function() {
                    $("body").find("#preloader").hide();
                    $("body").find("#preloader").find(".bg").remove();
                });

            }
        },

        /**
         * Добавляет указатель на текущую страницу в историю
         */
        pushState : function(c) {
            if (c.target == "modal") {
                this.stepsInModal = this.stepsInModal + 1;
            } else {
                this.stepsInModal = 0;
            }

            this._state("pushState", c);

        },
        /**
         * Заменяет указатель истории, если произошли изменения в модели
         */
        replaceState : function(c) {
            this._state("replaceState", c);

        },
        returnState : function(event) {
            if(event.state){
                var state = event.state;
                if (state.url) {
                    Yii.app.navigate(state.url, state.target ? state.target : null, {
                        scroll : false,
                        no_push : true,
                    });
                }
            }
        },
        _state : function(state, c)
        {
            var url = c.url;
            var data = {
                url : c.url,
                baseUrl: c.baseUrl,
                target: c.target
            }
            if (c.target == "modal")
            {
                data.modal = true;
                var amp = String(c.baseUrl).split("?");
                if (amp.length>1) {
                    amp = "&";
                } else {
                    amp = "?";
                }

                url = c.baseUrl + amp + (c.target == "modal" ? "z" : "x") + "=" + encodeURIComponent(c.url);
                data.url = c.baseUrl;
                if (c.target == "modal") {
                    data.zUrl = c.url;
                }
            }

            window.history[state](data, '1', url);
        }

    });

    Yii = typeof Yii != "undefined" ? Yii : {};

});