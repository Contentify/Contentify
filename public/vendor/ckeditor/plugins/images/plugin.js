CKEDITOR.plugins.add('images', {
    icons: 'images',
    init: function(editor) 
    {
        editor.addCommand('insertImages', 
        {
            exec: function(editor) {
                function setHandler($data)
                {
                    if (! $data) {
                        $data = $('.editor-images .images');
                    }

                    $data.find('.image').click(function()
                    {
                        var src = $(this).attr('data-src');
                        var img = '<img src="' + src + '">';

                        editor.insertHtml(img);
                        contentify.closeModal();
                    });
                }

                $.ajax({
                    url: contentify.baseUrl + 'editor-images',
                    type: 'GET'
                }).success(function(data)
                {
                    var $data = $(data);

                    contentify.modal('Images', $data);
                    setHandler($data);

                    function submit()
                    {
                        $.ajax({
                            url: contentify.baseUrl + 'editor-images',
                            type: 'POST',
                            data: { 'tag': $('.editor-images input[type=text]').val() }
                        }).success(function(data)
                        {
                            $('.editor-images .images').html(data);
                            setHandler(null);
                        }).fail(function(response)
                        {
                            contentify.alertRequestFailed(response);
                        });
                    }

                    $data.find('button').click(submit);
                    $data.find('input').keypress(function(event) {
                        if (event.which == 13) {
                            submit();
                        }
                    });
                }).fail(function(response)
                {
                    contentify.alertRequestFailed(response);
                });
            }
        });

        editor.ui.addButton('Images', 
        {
            label: 'Insert Images',
            command: 'insertImages',
            toolbar: 'insert'
        });
    }
});