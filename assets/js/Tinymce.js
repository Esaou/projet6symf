tinymce.init({
    selector: '.tinymce',
    toolbar_mode: 'floating',
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});