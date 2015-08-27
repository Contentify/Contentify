CKEDITOR.editorConfig = function( config ) {

    // Ref: http://ckeditor.com/latest/samples/plugins/toolbar/toolbar.html

    // Toolbar optimized for mobile devices
    config.toolbarGroups = [
        { name: 'links' },
        { name: 'insert' },       
        { name: 'basicstyles', groups: [ 'basicstyles' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
        { name: 'more', groups: [ 'mode' ] }
    ];

    config.removeButtons = 'Subscript,Superscript,Anchor,Styles,SpecialChar,Table';

    config.language = editorLocale; 

    config.uiColor = '#e6e6e6';

    config.extraPlugins = 'flags,templates,images';
};