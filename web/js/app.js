window.app = {

    dataAjax: null,

    ajax: function (action, params, async, method) {
        NProgress.start();
        if(!method)
            method = 'GET';

        $.ajax({
            url: action,
            type: method,
            data: params,
            async: (async) ? true : async,
            success: function (response) {
                if (response)
                    app.dataAjax = response;
            },
            error: function (request, status, error) {
                app.dataAjax = request.responseText;
            }
        });
        NProgress.done();
        return app.dataAjax;
    },

    gmail: {
        init: function () {
            this.MailModel = new MailModel;
            this.Mail = new MailView({
                model: this.MailModel,
                el: '#container-edit'
            });

            window.Router = new Router;

            this.NotificationModel = new NotificationModel;
            this.Notifi = new NotificationView({
                model: this.NotificationModel,
                el: '#container-massage'
            })

        }
    },

    sftp: {

        data: null,

        host_id : function () {
            return $("select#host option:selected").val();
        },

        command: function (url, param) {
            var data = app.ajax(url, param, false);
            var response = parseJson(data);
            if (response != null)
                return response;
        },

        init: function () {
            new SftpClientView({
                model: new SftpClientModel(),
                el: '#sftp'
            });
        }

    },
    
    error: function (message) {
        $('.errorModal').modal('toggle').find('.error-message').text(message);
    }
};

$(document).ready(function () {
    app.gmail.init();
    $('.mass').each(function () {
        var dateContainer = $(this).find('.udmass'),
            udate = dateContainer.html();
        dateContainer.html(convertUnixDate(udate));
    });
    $('.token').val(getToken());

    $('.status-daemons, .update-daemons').click(function () {
       var daemons = app.ajax('/daemon/status-daemons',{}, false);
       daemons = JSON.parse(daemons);
       if (daemons) $('.daemons-control').show().find('.list').html(daemons.list);
       return false;
    });
});

NProgress.configure({
    showSpinner: true,
    speed: 500
});

$(document).on('pjax:start', function () {
    NProgress.start();
});

$(document).on('pjax:end', function () {
    NProgress.done();
});

