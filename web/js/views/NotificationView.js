var NotificationView = Backbone.View.extend({

    events: {
        "click .hide-preview": 'hideMail',
        "click .get-mail": "hide"
    },

    initialize: function () {
        this.template = _.template($('#previewMail').html());
        this.listenTo(this.model, "change", this.render);
    },

    render: function () {
        if (this.model.has('data') === true) {
            var view = this.template({'mails': this.model.get('data')});
            this.$el.html(view);
            this.updateCountMail();
        }
    },

    updateCountMail: function () {
        $('.c-gmail').html(this.model.get('count'));
    },

    hideMail: function (event) {
        var link = $(event.currentTarget).closest('li').find('a.get-mail');
        this.hide(null, link);
    },

    hide: function (event, link) {
        var uid;
        var target;
        if (!event && link != null) {
            uid = link.attr('data-target');
            link.hide();
        } else {
            target = event.currentTarget;
            uid = $(target).attr('data-target');
            $(target).hide();
        }

        app.ajax('/mail/seen', {uid: uid});
        reduceCount('.c-gmail');
    }

});