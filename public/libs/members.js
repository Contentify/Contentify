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
                        $('<a>').append($('<img src="' + contentify.assetUrl + 'icons/delete.png">'))
                            .click(function() 
                            {
                                $.ajax({
                                    url: contentify.baseUrl + 'admin/members/delete/' + userId + '/' + id,
                                    type: 'DELETE'
                                }).success(function(data)
                                {
                                    render($td, userId, JSON.parse(data));
                                }).fail(function(response)
                                {
                                    contentify.alertRequestFailed(response);
                                });
                            })
                    )
                    .append(
                        $('<a>').append($('<img src="' + contentify.assetUrl + 'icons/user_edit.png">'))
                            .click(function()
                            {
                                $.ajax({
                                    url: contentify.baseUrl + 'admin/members/edit/' + userId + '/' + id,
                                    type: 'GET'
                                }).success(function(data)
                                {
                                    $.boxer($(data).append($('<button>').text('Save').click(function(event)
                                    {
                                        $.boxer('close');

                                        $.ajax({
                                            url: contentify.baseUrl + 'admin/members/update/' + userId + '/' + id,
                                            type: 'POST',
                                            data: {
                                                task:           $('#task').val(),
                                                description:    $('#description').val(),
                                                position:       $('#position').val(),
                                            }
                                        });
                                    })));
                                }).fail(function(response)
                                {
                                    contentify.alertRequestFailed(response);
                                });
                            })
                    )
                    .append(' ' + name)
            );
        });
        $td.append(
            $('<a class="add">').append($('<img src="' + contentify.assetUrl + 'icons/add.png">'))
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
                                    }).fail(function(response)
                                    {
                                        contentify.alertRequestFailed(response);
                                    });
                                })
                            ));
                        }
                    }).fail(function(response)
                    {
                        contentify.alertRequestFailed(response);
                    });
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