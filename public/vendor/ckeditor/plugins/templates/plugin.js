CKEDITOR.plugins.add('templates', {
    icons: 'templates',
    init: function(editor) 
    {
        editor.addCommand('insertTemplate', 
        {
            exec: function(editor) {
                $.ajax({
                    url: contentify.baseUrl + 'editor-templates',
                    type: 'GET'
                }).success(function(data)
                {
                    var $data = $(data);

                    $data.find('option').click(function()
                    {
                        $(this).off('click');

                        $.ajax({
                            url: contentify.baseUrl + 'editor-templates/' + this.value,
                            type: 'GET'
                        }).success(function(data)
                        {
                            editor.insertHtml(data);
                            contentify.closeModal();
                        }).fail(function(response)
                        {
                            contentify.alertRequestFailed(response);
                        }); 
                    });

                    contentify.modal('Templates', $data);
                }).fail(function(response)
                {
                    contentify.alertRequestFailed(response);
                });       
            }
        });

        editor.ui.addButton('Templates', 
        {
            label: 'Insert Template',
            command: 'insertTemplate',
            toolbar: 'insert'
        });
    }
});