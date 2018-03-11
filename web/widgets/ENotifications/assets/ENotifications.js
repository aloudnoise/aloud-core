$(function() {

    ENotifications = BaseWidget.extend({

        afterRender: function() {

            var that = this;

            var socket_notification = $(that.el).find(".notifications-icon .enotification-popover");
            $(socket_notification).find(".dismiss-popover").live("click", function() {
                $(socket_notification).removeClass("show");
                $(socket_notification).css("display", "none");
            });

            this.notifications = new BaseCollection(null, {
                yModel: "Notifications",
                live: "global",
                hash: Yii.app.user.notifications_hash
            });

            var SocketNotificationItem = BaseItem.extend({
                template : '#socket_notification_template',
                data: 'item',
                afterRender: function() {
                }
            });

            this.notifications.on('add', function(m) {

                console.log(m);

                var view = new SocketNotificationItem({
                    model : m,
                    parent : that,
                });
                m.view = view;

                $(socket_notification).find(".popover-body").append($(view.render().el));
                $(socket_notification).fadeIn(500);
                $(socket_notification).stopTime('hide-notifications');
                $(socket_notification).oneTime(10000, 'hide-notifications', function() {
                    $(socket_notification).fadeOut(500, function() {
                        that.notifications.each(function(r) {
                            that.notifications.remove(r);
                        });
                        that.notifications.remove(m);
                    });
                })

            }, this);

            this.notifications.on("remove", function(m) {
                if (m.view) {
                    m.view.remove();
                }
            })

        }

    })

})