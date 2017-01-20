var MailView = Backbone.View.extend({


    events: {
        "click .delete": "deleteFile",
        "click #view-attach": "openAttach",
        "click .reply-icon": "toReplyForm",
        "click .deleteAll": "deleteAll"
    },

    toReplyForm: function () {
        app.gmail.ReplyForm.scroll()
    },

    initialize: function () {
        this.template = _.template($('#templateMail').html());
        this.listenTo(this.model, "change", this.render);
    },

    render: function () {
        this.mail = JSON.parse(this.model.get('mail'));
        var view = this.template({
            'mail': this.mail
        });

        this.$el.html(view);
        this.open();
        this.initElements();

        app.gmail.ReplyForm = new ReplyFormView({
            model: this.model,
            el: '#replyForm'
        });
    },

    close: function () {
        this.$el.stop().animate({"right": '-100%'}, 200, function () {
            app.gmail.Mail.showPreload();
            app.gmail.Mail.model.clear({'silent':true});
        });
    },

    open: function () {
        this.$el.stop().animate({"right": '0'}, 200, function () {
            setTimeout(function () {
                app.gmail.Mail.setContentHeight();
                app.gmail.Mail.hidePreload();
            }, 2000);
        });

    },

    showPreload: function () {
        this.$el.find('#preload').css({'display': 'block'})
    },

    hidePreload: function () {
        this.$el.find('#preload').css({'display': 'none'})
    },

    setContentHeight: function () {
        $('iframe').css({
            'height': app.gmail.Mail.frame.find('body')[0].offsetHeight,
            'visibility': 'visible'
        });
    },

    initElements: function () {
        this.frame = this.$el.find('iframe').contents();
        var frameBody = this.frame.find('body'),
            frameHtml = this.frame.find('html');
        var DOM;
        if (this.mail.text_mail.textHtml) {
            var text = this.mail.text_mail.textHtml,
                body = frameBody.css({
                'height ': '!important',
                'margin': '0',
                'display': 'table'
            });
            DOM = $.parseHTML(text);
        }

        if (DOM.length == 1) DOM = wrapText(DOM[0].data);
        else DOM = text;

        frameBody.html(DOM.replaceAll('script', ''));
        frameHtml.css({
            'overflow': 'hidden',
            'width': '50%',
            'margin': '0 auto',
            'display':'table'
        });

        frameBody.find('blockquote').first()
            .wrap("<div class='show-quote'>Показать цитируемый текст</div>")
            .hide();
        frameBody.find('.show-quote').css({
            'color': 'brown',
            'cursor': 'pointer'
        }).on('click', function () {
            $(this).find('blockquote').first().slideToggle(0, function () {
                app.gmail.Mail.setContentHeight();
            })
        });

        this.handleEventFrame();
    },

    handleEventFrame: function () {
        this.frame.on('click', 'a', function () {
            var href = $(this).attr('href'),
                pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
                text = getEmailFrom($(this).text()),
                form = app.gmail.ReplyForm;

            if (href.indexOf('o:') != -1) {
                form.reply(href.replace('mailto:', ''));
                return false;
            }

            if (pattern.test(text)) form.reply(text);
            return false;
        });
    },


    openAttach: function () {
        this.$el.find('.attachment-block').slideToggle()
    },

    deleteFile: function (event) {
        var uid = $(event.currentTarget).attr('id');
        if (confirm('Точно удалить?') === true) {
            app.ajax('/mail/deletefile', {uid: uid});
            $(event.currentTarget).closest('li').hide();
            reduceCount('.c-attach');
        }
    },

    deleteAll: function (event) {
        var button = $(event.currentTarget);
        if (confirm("Сообщение будет удалено, продолжить?")) {
            app.ajax('/mail/deletemail', {uid: button.attr('id')});
            button.removeClass('btn-danger deleteAll')
                .addClass('btn-warning')
                .html('<i class="fa fa-check" aria-hidden="true"></i> Удалено')
        }
    }

});
