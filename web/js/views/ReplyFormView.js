var ReplyFormView = Backbone.View.extend({


    events: {
        'click .fa-close': "deleteFile"
    },

    initialize: function () {
        this.listenTo(this.model, 'change', this.initDropzone());

    },

    initDropzone: function () {
        Dropzone.autoDiscover = false;
        this.dropzone = new Dropzone('.dropzone', {
            paramName: "UploadFile[file]",
            url: '/mail/upload',
            headers: {"X-CSRF-Token": getToken()}
        });

        this.dropzone.on("success", function (file) {
            app.gmail.ReplyForm.addFile(file.name)
        });
    },

    reply: function (email) {
        var input = this.$el.find('#to'),
            lastEmail = input.val(),
            emails = lastEmail.split(','),
            error = false;

        emails.forEach(function (elem) {
            if (elem == email)
                error = true;
        });

        if (error == true)return false;
        if (lastEmail) input.val(lastEmail + ',' + email);
        else input.val(email);
        this.scroll();
    },

    scroll: function () {
        $('#container-edit').animate({
            scrollTop: this.$el.offset().top
        }, 500);
    },

    addFile: function (fileName) {
        if (this.$el.find('div[id="'+fileName+'"]').length === 0) {
            $("<div/>", {html: fileName, id: fileName, class: "attachFile"})
            .append('<i class="fa fa-close" aria-hidden="true"></i>')
            .appendTo(this.$el.find('.attach-block'));
        }
    },

    deleteFile: function (event) {
        var target = $(event.currentTarget),
            parent = target.parent('div'),
            fileName = parent.attr('id');

        app.ajax('/mail/deletefile', {fileName: fileName});
        this.$el.find('span:contains(' + fileName + ')')
            .closest('.dz-preview').remove();
        parent.remove();
    }

});
