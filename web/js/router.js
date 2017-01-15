var Router = Backbone.Router.extend({

    response: null,

    routes: {
        'mail/:uid': 'getMail',
        '': 'goHome'
    },

    initialize: function () {
        Backbone.history.start();

    },

    getMail: function (uid) {
        var data = app.ajax('/mail/get', {uid: uid}, false);
        if (typeof data === 'string') {
            app.gmail.MailModel.set({'mail': data});
        } else app.error("Возможно вы удалили письмо.")


    },

    goHome: function () {
        app.gmail.Mail.close();
    }

});