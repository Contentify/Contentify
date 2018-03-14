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
                        $('<a class="icon-link">').append($(contentify.fontIcon('trash')))
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
                        $('<a class="icon-link">').append($(contentify.fontIcon('edit')))
                            .click(function()
                            {
                                $.ajax({
                                    url: contentify.baseUrl + 'admin/members/edit/' + userId + '/' + id,
                                    type: 'GET'
                                }).success(function(data)
                                {
                                    var $footer = $('<button>').text(contentify.translations.save).click(function(event)
                                    {
                                        $.ajax({
                                            url: contentify.baseUrl + 'admin/members/update/' + userId + '/' + id,
                                            type: 'POST',
                                            data: {
                                                task:        $('#task').val(),
                                                description: $('#description').val(),
                                                position:    $('#position').val()
                                            }
                                        }).fail(function(response)
                                        {
                                            contentify.alertRequestFailed(response);
                                        });

                                        contentify.closeModal();
                                    });

                                    contentify.modal(contentify.translations.object_team, data, $footer);
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
            $('<a class="add icon-link">').append($(contentify.fontIcon('plus-circle')))
                .click(function()
                {
                    $.ajax({
                        url: contentify.baseUrl + 'admin/members/add/' + userId,
                        type: 'GET'
                    }).success(function(data)
                    {
                        if (data) {
                            var $footer = $('<button>').text(contentify.translations.add).click(function()
                            {
                                var teamId = $(this).parent().parent().find('select').val();

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

                                contentify.closeModal();
                            });

                            contentify.modal(contentify.translations.object_team, data, $footer);
                        } else {
                            contentify.alertInfo(contentify.translations.not_found + ' (' +
                                contentify.translations.object_team + ')');
                        }
                    }).fail(function(response)
                    {
                        contentify.alertRequestFailed(response);
                    });
                })
        );
    }

    $('td div.data').each(function() {
        var $td    = $(this).parent();
        var userId = $(this).attr('data-user-id');
        var teams  = JSON.parse($(this).text());

        render($td, userId, teams);
    });
});