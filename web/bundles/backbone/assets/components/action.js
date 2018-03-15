$(function() {

    BaseAction = BaseComponent.extend({

        controller : null,
        defineArguments : function(args)
        {
            this.controller = args.controller;
        },
        ga : function() {
            if (typeof ga != "undefined") {
                ga('send', {
                    'hitType': 'pageview',
                    'page': this.controller.url
                });
            }
        },
        _afterRender : function(args) {
            var that = this;
            console.log(this.el);
            $(this.el).data('action', this);
            var from = this.controller.model.get("GET").from;
            if (from) {
                $(".assign-item a").click(function(e) {

                    e.stopPropagation();
                    e.preventDefault();

                    if (from[2] == 'assign') {
                        var Assign = new BaseModel({
                            related_id: $(this).parents(".assign-item").attr("assign_id"),
                            related_model: $(this).parents(".assign-item").attr("assign_item"),
                            target_id: from[1],
                            target_model: from[0]
                        }, {
                            route: 'assign'
                        });

                        Assign.save({}, {
                            success: function (model, response, xhr) {
                                if (that.controller.target == "modal") {
                                    $(that.controller.el).modal("hide");
                                } else {
                                    Yii.app.navigate(that.controller.model.get("GET").return ? that.controller.model.get("GET").return : window.location.href, null, {
                                        scroll: false,
                                        no_push: true,
                                        base_render: true
                                    });
                                }
                            }
                        });
                        return false;
                    }

                    if (from[2] == 'redirect') {

                        Yii.app.navigate(Yii.app.createOrganizationUrl(from[3], {
                            target_model: from[0],
                            target_id: from[1],
                            related_model: $(this).parents(".assign-item").attr("assign_item"),
                            related_id : $(this).parents(".assign-item").attr("assign_id")
                        }));

                    }

                });
            }

        },
        _destroy : function() {
            // override if need
        },
        __destroy: function() {
            this._destroy();
            $(this.el).find("*").each(function() {
                $(this).off();
            });
            delete this.controller;
        }
    })

})
