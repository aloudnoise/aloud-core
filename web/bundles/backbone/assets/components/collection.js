$(function() {
    /**
     *
     * @type Backbone.Collection
     */
    BaseCollection = Backbone.Collection.extend({
        yModel : null,
        pager : false,
        url:CORE.API_URL,
        model: BaseModel,
        schema : null,
        sync:BaseSync,
        noLoading : false,
        live: false,
        hash: null,
        setUrl: function(url, absolute) {
            if (!absolute) {
                this.url = this.url + url;
            } else {
                this.url = url;
            }
        },
        initialize : function(models, options)
        {
            var that = this;
            if (options && options.yModel) {
                this.yModel = options.yModel
            }
            if (options && options.schema) {
                this.schema = options.schema;
            }
            if (options && options.route) {
                this.setUrl(options.route);
            }
            if (options && options.pager) {
                this.pager = options.pager
            }
            if (options && options.noLoading) {
                this.noLoading = options.noLoading
            }
            if (options && options.live) {
                this.live = options.live;
            }
            if (options && options.hash) {
                this.hash = options.hash;
            }

            if (this.live) {
                if (Yii.app.socket) {
                    if (!Yii.app.socket.registered) {
                        if (Yii.app.socket.connection) {
                            Yii.app.socket.connection.on("registration_complete", function () {
                                that.registerLive();
                            })
                        }
                    } else {
                        that.registerLive();
                    }
                }

            }

            this.on("error",this.error, this);
        },
        registerLive: function() {
            console.log('registering event');
            var that = this;
            if (that.hash) {
                var hash = that.hash;
                Yii.app.socket.connection.emit("register_event", {
                    hash: hash,
                    model: that.yModel,
                    scope: that.live
                });

                Yii.app.socket.addEvent(hash, that.live, function() {
                    this.connection.on(hash, function(models) {
                        that.set(models, {
                            remove: false
                        });
                    })
                });

            }
        },
        parse: function (response, options) {
            if (this.pager) {
                if (options.xhr.getResponseHeader('x-pagination-page-count')) {
                    this.pager.set("total", options.xhr.getResponseHeader('x-pagination-page-count'));
                }
                this.pager.set("page", options.xhr.getResponseHeader('x-pagination-current-page'));

            }
            return BaseCollection.__super__.parse(response, options);
        },
        error : function(collection, response, options)
        {
            new BaseModel().callError(response);
        }
    });
})