$(function() {

    Socket = BaseComponent.extend({

        connection: null,
        registered: false,
        events: [],
        addEvent: function(hash, scope, callback) {
            this.events.push({
                hash: hash,
                scope: scope
            });
            if (typeof callback == 'function') {
                callback.apply(this);
            }
        },
        start: function() {

            var that = this;
            if (typeof io != "undefined") {

                this.connection = io(CORE.SOCKET_URL);
                this.connection.on("connect", function () {

                    console.log('connected');
                    that.connection.emit("register", {
                        user_id: Yii.app.user.id
                    });

                    that.connection.on("registration_complete", function () {
                        that.registered = true;
                    });



                });
            }

        },
        clear: function() {
            var that = this;
            if (typeof io != "undefined") {
                if (that.connection) {
                    if (that.registered) {
                        that.connection.emit("clear_events");
                    }
                }
                var events = [];
                _(this.events).each(function(event) {
                    if (event.scope != 'global') {
                        that.connection.off(event.hash);
                    } else {
                        events.push(event);
                    }
                });
                delete that.events;
                that.events = events;
            }
        }
    })

})