var MailManager = Backbone.View.extend({

    el: $('#mail-wrap'),

    events: {
        "click .get": "updateNotification",
        "ajaxComplete": 'initLabelauty'
    },

    initialize: function () {
        this.initLabelauty();
    },

    initLabelauty: function() {
        this.$el.find(':checkbox').labelauty({
            label: false,
            minimum_width: "20px"
        });
    },

    updateNotification: function (event) {
        var link = $(event.currentTarget);
        if (link.attr('data-num') == '0') {
            app.gmail.Notifi.hide(link.attr('data-target'));
            link.closest('div.unseen').removeClass('unseen')
        }
    }


});

