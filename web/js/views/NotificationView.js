var NotificationView = Backbone.View.extend({

    events: {
        "click .hide-preview": 'hideMail',
        "click .get-mail": "hideMail"
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
        this.hide(link.attr('data-target'));
    },

    hide: function (uid) {
        var target = $('a.get-mail[data-target='+uid+']').closest('li');
        target.hide();
        app.ajax('/mail/seen', {uid: uid});
        reduceCount('.c-gmail');
    }

});