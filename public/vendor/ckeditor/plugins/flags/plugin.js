CKEDITOR.plugins.add('flags', {
    icons: 'flags',
    init: function(editor) 
    {
        editor.addCommand('insertFlag', 
        {
            exec: function(editor) {
                function addFlag(flag)
                {
                    var url = contentify.assetUrl + 'uploads/countries/' + flag + '.png';
                    return '<img src="' + url + '" title="' + flag + '" alt="' + flag + '"> ';
                }

                var $flags = $('<div class="editor-flags">' + addFlag('eu') 
                    + addFlag('at')
                    + addFlag('de')
                    + addFlag('dk')
                    + addFlag('es') 
                    + addFlag('fi')
                    + addFlag('fr')
                    + addFlag('gr')
                    + addFlag('it')
                    + addFlag('nl')
                    + addFlag('pl')
                    + addFlag('pt')
                    + addFlag('ru')
                    + addFlag('se')
                    + addFlag('uk')
                    + addFlag('us')
                    + '</div>');
                
                $flags.find('img').click(function()
                {
                    editor.insertHtml(this.outerHTML);
                    contentify.closeModal();
                })
                
                contentify.modal('Flags', $flags);
            }
        });

        editor.ui.addButton('Flags', 
        {
            label: 'Insert Flag',
            command: 'insertFlag',
            toolbar: 'insert'
        });
    }
});