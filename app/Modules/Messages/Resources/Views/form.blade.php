@include('messages::page_navigation', ['active' => 'create'])

{!! Form::errors($errors) !!}

{!! Form::open(['url' => 'messages']) !!}
    {!! Form::smartText('receiver_name', trans('messages::receiver'), isset($username) ? $username : null) !!}

    {!! Form::smartText('title', trans('app.title'), isset($title) ? $title : null) !!}

    {!! Form::smartTextarea('text', trans('app.text'), false, isset($text) ? $text : null) !!}

    {!! Form::actions(['submit' => trans('app.send')]) !!}
{!! Form::close() !!}

<script>
    // Prevent users from losing their unsent message
    // when clicking on another tab (inbox / outbox)
    $(window).on('beforeunload', function()
    {
        if ($('#receiver_name').val() || $('#title').val() || $('#text').val()) {
            return ''; // Will force the browser to show a confirmation dialogue
        }
    });

    // Don't show the confirm dialogue when the user clicks on the submit button
    $('.page .form-actions').click(function(event)
    {
        $(window).off('beforeunload');
    });
</script>
