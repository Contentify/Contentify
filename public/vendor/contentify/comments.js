$(document).ready(function()
{
    var foreignType = $('#comments').attr('data-foreign-type');
    var foreignId   = $('#comments').attr('data-foreign-id');

    var $form       = $('.create-comment form');
    var $textarea   = $('.create-comment textarea');

    $('.create-comment .save').click(function()
    {
        var $self   = $(this);
        var text    = $textarea.val();
        $form.hide();

        $.ajax({
            url: contentify.baseUrl + 'comments/store',
            type: 'POST',
            data: {
                text: text,
                foreigntype: foreignType,
                foreignid: foreignId
            }
        }).success(function(data)
        {
            $('#comments').append(data);
        }).fail(function(response)
        {
            contentify.alertRequestFailed(response);
        });
    });

    var editClickHandler = function()
    {
        event.preventDefault();

        $('.create-comment .save').unbind('click');

        var $self   = $(this);
        var id      = $self.attr('data-id');

        $.ajax({
            url: contentify.baseUrl + 'comments/' + id,
            type: 'GET'
        }).success(function(comment)
        {
            $form.show();
            $textarea.val(comment.text).focus();

            $('.create-comment .save').click(function()
            {
                $form.hide();

                $.ajax({
                    url: contentify.baseUrl + 'comments/' + comment.id + '/update',
                    type: 'PUT',
                    data: { 
                        text: $textarea.val(), 
                        foreigntype: foreignType, 
                        foreignid: foreignId
                    }
                }).success(function(data)
                {
                    var $el = $self.parent().html(data);
                    $el.find('.edit').click(editClickHandler);
                    $el.find('.delete').click(editClickHandler);
                }).fail(function(response)
                {
                     $self.parent().html(response.responseText);
                });
            });
        }).fail(function(response)
        {
            $self.parent().html('');
            contentify.alertRequestFailed(response);
        });
    };

    $('.page').on('click', '.comment .edit', editClickHandler);

    var quoteClickHandler = function(event)
    {
        event.preventDefault();

        var $self       = $(this);
        var id          = $self.attr('data-id');
        var creator     = $self.parent().find('.creator-name').text();

        if (creator) creator = '=' + creator;

        $.ajax({
            url: contentify.baseUrl + 'comments/' + id,
            type: 'GET'
        }).success(function(comment)
        {
            $textarea.val($textarea.val() + '[quote' + creator + ']' + comment.text + '[/quote]\n').focus();
        }).fail(function(response)
        {
            $self.parent().html('');
            contentify.alertRequestFailed(response);
        });
    };

    $('.page').on('click', '.comment .quote', quoteClickHandler);

    var deleteClickHandler = function(event)
    {
        event.preventDefault();

        var $self = $(this);
        var id = $self.attr('data-id');

        $.ajax({
            url: contentify.baseUrl + 'comments/' + id + '/delete',
            type: 'DELETE'
        }).success(function()
        {
            $self.parent().remove();
        }).fail(function(response)
        {
             $self.parent().html('');
             contentify.alertRequestFailed(response);
        });
    };

    $('.page').on('click', '.comment .delete', deleteClickHandler);

    paginateClickHandler = function(event)
    {
        event.preventDefault();

        var url = $(this).attr('href');
        var pos = url.indexOf('page=');
        var page = url.substr(pos + 5);
        page = parseInt(page);
        
        $.ajax({
            url: contentify.baseUrl + 'comments/paginate/' + foreignType + '/' + foreignId,
            type: 'GET',
            data: { 
                page: page
            }
        }).success(function(data)
        {
            $('#comments').html(data);

            $('.comment .edit').click(editClickHandler);
            $('.comment .quote').click(quoteClickHandler);
            $('.comment .delete').click(deleteClickHandler);
            $('#comments .pagination a').click(paginateClickHandler);
        }).fail(function(response)
        {
            contentify.alertRequestFailed(response);
        });
    };

    $('#comments .pagination a').click(paginateClickHandler);
});