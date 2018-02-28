$(function() {

    EMessages = BaseWidget.extend({

        afterRender: function() {

            var that = this;

            this.messages = new BaseCollection(null, {
                yModel: "Messages",
                live: "global",
                hash: Yii.app.user.messages_hash
            });

            this.messages.on('add', function(m) {
                console.log(m);
                $(that.el).find(".sound").html('<audio autoplay="autoplay"><source src="' + BASE_ASSETS + '/media/new_message.mp3" type="audio/mpeg" /><embed hidden="true" autostart="true" loop="false" src="' + BASE_ASSETS + '/media/new_message.mp3" /></audio>');
                var i = parseInt($(that.el).find(".side-profile").find(".messages-side-menu").find(".new-count").html());
                i++;
                if (isNaN(i)) {
                    i = 1;
                }
                console.log($(that.el).find(".side-profile").find(".messages-side-menu").find(".new-count"));
                $(that.el).find(".side-profile").find(".messages-side-menu").find(".new-count").html(i);

                if ($(that.el).find(".chat-container[container_id='"+m.get("chat_id")+"']").length) {
                    $(that.el).find(".chat-container[container_id='"+m.get("chat_id")+"']").each(function() {

                        var message = _.template($("#message_template").html());

                        $(this).append(message({
                            data : m.toJSON()
                        }));

                    })
                }

            }, this);

        }

    })

})