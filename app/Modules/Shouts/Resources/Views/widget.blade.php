<div id="shoutbox" class="widget widget-shouts">
    <ul class="messages list-unstyled">
        <?php for($i = sizeof($shouts) - 1; $i >= 0; $i--) { 
            $shout = $shouts[$i]; ?>
            <li>
                <a class="user" href="{{ url('users/'.$shout->creator->id) }}">{!! $shout->creator->username !!}:</a><span class="text">{!! $shout->text !!}</span>
            </li>
        <?php } ?>
    </ul>

    <input type="text" name="shout_text" <?php if (! user()) echo 'readonly="readonly"' ?> >

    <a class="shout" href="#" <?php if (user()) echo 'data-enabled="1"' ?>>{!! trans('app.send') !!}</a>
</div>

<script>
    $(document).ready(function()
    {
        var refreshInterval = 5000; // Milliseconds between the refreshes

        var $shoutbox       = $('#shoutbox');
        var $messages       = $shoutbox.find('ul.messages');
        var $text           = $shoutbox.find('input[type=text]');
        var lastUpdateId    = 0;
        var shoutStore      = [];

        function receiveShouts(updateId)
        {
            $.ajax({
                url: contentify.baseUrl + 'share/shouts.json',
            }).done(function(shouts)
            { 
                if (shoutStore.length == 0) {
                    $messages.html('');
                }

                $.each(shouts, function(index, shout)
                {
                    if (shoutStore.indexOf(shout.id) === -1) {
                        var $message = $('<li><a class="user" href="' + contentify.baseUrl + 'users/' + shout.creator_id + '">' + shout.creator_username + ':</a><span class="text">' + shout.text + '</span></li>');
                        $messages.append($message);
                        shoutStore.push(shout.id);
                    }
                });

                $messages.find('li.temp').remove();
                lastUpdateId = updateId;
            });
        }

        function refresh()
        {
            $.ajax({
                url: contentify.baseUrl + 'share/shouts.beacon',
                cache: false
            }).done(function(data)
            { 
                var updateId = parseInt(data);

                if (lastUpdateId == 0) {
                    lastUpdateId = updateId;
                }

                if (updateId > lastUpdateId) {
                    receiveShouts(updateId);
                }
            });

            window.setTimeout(refresh, refreshInterval);
        }

        $shoutbox.find('.shout').click(function(event)
        {
            event.preventDefault();

            if ($(this).attr('data-enabled') != '1') {
                return;
            }

            var text = $text.val();

            if (text != '') {
                @if (user())
                    var username = '{!! user()->username !!}';
                    var id = {!! user()->id !!};
                @endif
                
                var $message = $('<li class="temp"><a class="user" href="' + contentify.baseUrl + 'users/' + id + '">' + username + ':</a><span class="text">' + text + '</span></li>');
                $messages.append($message);

                $text.val('');

                $.post(contentify.baseUrl + 'shouts', { text: text }, function(data) 
                { 
                    
                }).fail(function()
                {
                    $messages.find('li.temp').remove();
                });
            }
        });

        refresh();
    })
</script>