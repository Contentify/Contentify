$(document).ready(function()
{
    function render($td, userId, teams)
    {
        $td.empty();
        
        $td.attr('data-user-id', userId);
        $.each(teams, function(id, name)
        {
            var $self = $(this);
            $td.append(
                $('<div>').attr('data-team-id', id)
                    .append(
                        $('<a>').append($('<img src="' + contentify.baseUrl + 'icons/delete.png">'))
                            .click(function() 
                            {
                                $.ajax({
                                    url: contentify.baseUrl + 'admin/members/delete/' + userId + '/' + id,
                                    type: 'DELETE'
                                }).success(function(data)
                                {
                                    render($td, userId, JSON.parse(data));
                                }).error(function(response)
                                {
                                    var x=1;
                                });
                            })
                    )
                    .append(
                        $('<a>').append($('<img src="' + contentify.baseUrl + 'icons/user_edit.png">'))
                            .click(function()
                            {
                                $.ajax({
                                    url: contentify.baseUrl + 'admin/members/edit/' + userId + '/' + id,
                                    type: 'GET'
                                }).success(function(data)
                                {
                                    $.boxer($(data));
                                });
                            })
                    )
                    .append(name)
            );
        });
        $td.append(
            $('<a class="add">').append($('<img src="' + contentify.baseUrl + 'icons/add.png">'))
                .click(function()
                {
                    $.ajax({
                        url: contentify.baseUrl + 'admin/members/add/' + userId,
                        type: 'GET'
                    }).success(function(data)
                    {
                        if (data) {
                            $.boxer($(data).append(
                                $('<button>').text('Add').click(function()
                                {
                                    var teamId = $(this).parent().find('select').val();
                                    $.boxer('close');
                                    $.ajax({
                                        url: contentify.baseUrl + 'admin/members/add/' + userId + '/' + teamId,
                                        type: 'POST'
                                    }).success(function(data)
                                    {
                                        render($td, userId, JSON.parse(data));
                                    }).error(function(response)
                                    {
                                        console.log(response);
                                    });
                                })
                            ));
                        }
                    })
                })
        );
    }

    $('td div.data').each(function() {
        var $td     = $(this).parent();
        var userId  = $(this).attr('data-user-id');
        var teams   = JSON.parse($(this).text());

        render($td, userId, teams);
    });
});