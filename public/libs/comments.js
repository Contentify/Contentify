$(document).ready(function()
{
    var foreignType = $('#comments').attr('data-foreign-type');
    var foreignId   = $('#comments').attr('data-foreign-id');

    $('.create-comment .save').click(function()
    {
        var $self   = $(this);
        var text    = $('.create-comment textarea').val();
        $('.create-comment form').remove();

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
        }).error(function(response)
        {
            $('.create-comment').html(response.responseText);
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
            $('.create-comment textarea').val(comment.text).focus();

            $('.create-comment .save').click(function()
            {
                $.ajax({
                    url: contentify.baseUrl + 'comments/' + comment.id + '/update',
                    type: 'PUT',
                    data: { 
                        text: $('.create-comment textarea').val(), 
                        foreigntype: foreignType, 
                        foreignid: foreignId
                    }
                }).success(function(data)
                {
                    $('.create-comment textarea').val('');
                    var $el = $self.parent().html(data);
                    $el.find('.edit').click(editClickHandler);
                    $el.find('.delete').click(editClickHandler);
                }).error(function(response)
                {
                     $self.parent().html(response.responseText);
                });
            });
        }).error(function(response)
        {
            $self.parent().html(response.responseText);
        });
    };

    $('.comment .edit').click(editClickHandler);

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
            var $textarea    = $('.create-comment textarea');
            $textarea.val($textarea.val() + '[quote' + creator + ']' + comment.text + '[/quote]\n').focus();
        }).error(function(response)
        {
            $self.parent().html(response.responseText);
        });
    };

    $('.comment .quote').click(quoteClickHandler);

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
        }).error(function(response)
        {
             $self.parent().html(response.responseText);
        });
    };

    $('.comment .delete').click(deleteClickHandler);
});