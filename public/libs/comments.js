$(document).ready(function()
{
    $('.create-comment .save').click(function()
    {
        var $self = $(this);

        $.ajax({
            url: baseUrl + 'comments/store',
            type: 'POST',
            data: { text: $('.create-comment textarea').val(), foreigntype: foreignType, foreignid: foreignId }
        }).success(function()
        {
            $('.create-comment').remove();
            location.reload(); // TODO
        }).error(function(response)
        {
            $self.parent().html(response.responseText);
        });
    });

    $('.comment .edit').click(function()
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
                    data: { text: $('.create-comment textarea').val(), foreigntype: foreignType, foreignid: foreignId }
                }).success(function()
                {
                    $('.create-comment').remove();
                    location.reload(); // TODO
                }).error(function(response)
                {
                     $self.parent().html(response.responseText);
                });
            });
        }).error(function(response)
        {
            $self.parent().html(response.responseText);
        });
    });

    $('.comment .delete').click(function(event)
    {
        event.preventDefault();

        var $self = $(this);
        var id = $self.attr('data-id');

        $.ajax({
            url: baseUrl + 'comments/' + id + '/delete',
            type: 'DELETE'
        }).success(function()
        {
            $self.parent().remove();
        }).error(function(response)
        {
             $self.parent().html(response.responseText);
        });
    });
});