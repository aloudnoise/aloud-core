$(function() {


    EmbedVideo = BaseWidget.extend({

        el : null,
        afterRender : function() {

            var player;
            var that = this;

            var played = 0;
            function onPlayerStateChange(event) {
                if (player.getPlayerState() == 1) {
                    $(window).everyTime(1000,"playing_video", function() {
                        played++;
                    });
                }
                if (player.getPlayerState() == 2 || player.getPlayerState() == 3) {
                    $(window).stopTime("playing_video");
                }
                if (player.getPlayerState() == 0) {
                    $(window).stopTime("playing_video");
                    finished();
                }
            }

            function showPlayer() {

                player = new YT.Player($(that.el).attr("id"), {
                    height: $(that.el).attr("height"),
                    width: $(that.el).attr("width"),
                    videoId: $(that.el).attr("id"),
                    events: {
                        'onStateChange': onPlayerStateChange
                    }
                });

            }

            if (!PLAYER_READY) {
                window.onYouTubePlayerAPIReady = function () {
                    showPlayer();
                };
            } else {
                showPlayer();
            }


            function finished()
            {
                console.log(played);
            }

        }

    });

})