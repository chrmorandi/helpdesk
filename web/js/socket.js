var conn = new ab.Session('ws://helpdesk:8080',

    function () {
        conn.subscribe('mails', function (topic, data) {
            console.log(data);
            app.gmail.NotificationModel.set(data);
        });
    },
    function () {
        console.warn('WebSocket connection closed');
    },
    {
        'maxRetries': 60,
        'retryDelay': 4000,
        'skipSubprotocolCheck': true

    }
);
