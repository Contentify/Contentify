$(document).ready(function()
{
    var foreignType = $('#comments').attr('data-foreign-type');
    var foreignId   = $('#comments').attr('data-foreign-id');
    var token       = $('#comments').attr('data-token'); // CSRF token

    $('.create-comment .save').click(function()
    {
        var $self = $(this);
        var text = $('.create-comment textarea').val();
        $('.create-comment').remove();

        $.ajax({
            url: baseUrl + 'comments/store',
            type: 'POST',
            data: { text: text, foreigntype: foreignType, foreignid: foreignId, _token: token }
        }).success(function(data)
        {
            $('#comments').append(data);
        }).error(function(response)
        {
            $self.parent().html(response.responseText);
        });
    });

    var editClickHandler = function()
    {
        event.preventDefault();

        $('.create-comment .save').unbind('click');

        var $self = $(this);
        var id = $self.attr('data-id');

        $.ajax({
            url: baseUrl + 'comments/' + id,
            type: 'GET'
        }).success(function(comment)
        {
            $('.create-comment textarea').val(comment.text);

            $('.create-comment .save').click(function()
            {
                $.ajax({
                    url: baseUrl + 'comments/' + comment.id + '/update',
                    type: 'PUT',
                    data: { text: $('.create-comment textarea').val(), foreigntype: foreignType, foreignid: foreignId, _token: token }
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

    var deleteClickHandler = function(event)
    {
        event.preventDefault();

        var $self = $(this);
        var id = $self.attr('data-id');

        $.ajax({
            url: baseUrl + 'comments/' + id + '/delete',
            type: 'DELETE',
            data: { _token: token }
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