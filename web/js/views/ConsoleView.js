var ConsoleView = Backbone.View.extend({

    events: {
        "keypress": "execute",
        'click .c-close': 'toggle'
    },


    initialize: function () {
        this.$el.draggable({handle: '.drag-console', containment: ".wrapper"})
            .resizable().click(function () {
                $('#command').focus();
            });

        this.template = _.template($('#consoleTpl').html());
        this.listenTo(this.model, "change", this.render);
        this.render();
    },

    render: function () {
        var view = this.template({'model': this.model.toJSON()});
        this.$el.append(view);
        this.$el.find('#command').focus()
    },

    execute: function (e) {
        var target = event.currentTarget;
        var element = $(target).find('input');
        var command = element.val();
        var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
        if (charCode == 13) {
            element.replaceWith("<p class='con-block'>" + command + "</p>");
            var response = app.sftp.command('/sftp/execute', {
                command: command,
                hostId: app.sftp.host_id()
            });
            this.model.set(response);
        }
    },

    flush: function () {
        if (this.model) {
            this.model.clear();
            this.$el.find('.tplConsole').remove();
            this.model.set(this.model.defaults)
        }
    },

    focus: function () {
        this.$el.find('#command').focus();
    },

    toggle: function () {
        this.$el.toggle()
    }

});