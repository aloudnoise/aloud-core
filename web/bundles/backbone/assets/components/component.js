$(function() {

    /**
     * Класс базового компонента, который наследуют контроллер, действие, представление
     * @type {*|void|extend|extend|extend|extend}
     */
    BaseComponent = Backbone.View.extend({

        parent: null,
        events: {},
        defineArguments: function (args) {

        },
        initialize: function (args) {
            if (args && args.parent) {
                this.parent = args.parent;
            }
            this.defineArguments(args);
            this.beforeInitialize(args);
            if (typeof args != "undefined" && args.events) {
                this.events = args.events;
                delete(args.events);
            }

            this._initialize(args);
            this.afterInitialize(args);
            return this;
        },

        _initialize: function (args) {

        },

        /**
         *  Вызыывается перед инициализацией контроллера
         */
        beforeInitialize: function (args) {

        },

        /**
         * Вызывается после инициализации контроллера
         * @param args
         */
        afterInitialize: function (args) {

        },

        render: function (args) {
            this.beforeRender(args);
            this._render(args);
            this.afterRender(args);
            this._afterRender(args);
            return this;
        },

        _render: function (args) {

        },

        /**
         * Вызывается перед рендерингом контроллера
         */
        beforeRender: function (args) {

        },

        /**
         * Вызывается после рендеринга контроллера
         */
        afterRender: function (args) {

        },

        /**
         * Вызывается после рендеринга контроллера для внутренних компонентов
         */
        _afterRender: function (args) {

        }


    })
});