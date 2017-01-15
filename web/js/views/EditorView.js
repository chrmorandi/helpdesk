var EditorView = Backbone.View.extend({

    el: ".control-editor",
    container: $('#editor'),
    evalBlock: $('.eval-code'),
    aceEditor: null,

    events: {
        'click .close-edit': 'close',
        'click .run-edit': 'run',
        'click .save-edit': 'save',
        'click .hide-result': 'hideEval'
    },

    model: Backbone.Model.extend({}),

    initialize: function () {
        this.aceEditor = ace.edit("editor");
    },

    load: function (string, mode) {
        this.$el.find('.run-edit').attr('disabled', true);
        this.$el.find('.save-edit').removeAttr('disabled');

        switch (mode) {
            case 'js':
                mode = 'javascript';
                break;
        }
        this.aceEditor.getSession().setMode('ace/mode/' + mode);


        this.aceEditor.setValue(string, -1);
        this.open();
    },

    run: function () {
        var eval = this.aceEditor.getValue();
        var result = app.ajax('/edit/eval', {
            code: eval
        }, false, 'POST');
        this.showEval(result);
    },

    showEval: function (code) {
        if (code) {
            this.evalBlock.text(code).animate({"bottom": 0}, 300);
            this.$el.find('.hide-result').removeAttr('disabled');
        }

    },

    hideEval: function () {
        this.evalBlock.animate({"bottom": "-100%"}, 300, function () {
            $(this).text('')
        });
        this.$el.find('.hide-result').attr('disabled', true);
    },

    close: function () {
        this.container.stop().animate({"right": '-100%'}, 200, function () {
            $('.control-editor').animate({'top': '-100%'}, 500)
        });
        $('.wrapper').css({'overflow': 'hidden'});
        this.hideEval();
    },

    clickOpen: function () {
        this.$el.find('.run-edit').removeAttr('disabled');
        this.$el.find('.save-edit').attr('disabled', true);
        this.open();
    },

    open: function () {
        this.container.removeAttr('style');
        this.container.css({
            'position': 'absolute',
            'min-height': '100%'
        });
        $('.wrapper').css({'overflow': 'visible'});
        this.container.stop().animate({
            "right": '0'
        }, 200, function () {
            $('.control-editor').animate({'top': '50px'}, 500)
        });

    }
});

var editor = new EditorView();

$('.open-editor').click(function () {
    editor.clickOpen();
});