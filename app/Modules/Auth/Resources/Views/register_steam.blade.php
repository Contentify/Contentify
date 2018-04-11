<h1 class="page-title">{{ trans_object('registration') }}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => 'auth/steam')) !!}
    {!! Form::smartText('username', trans('app.username'), $username) !!}

    <div id="username_info" class="help-block text-right hidden">
        {!! HTML::fontIcon('info-circle') !!} {{ trans('auth::username_taken') }}
    </div>

    {!! Form::smartEmail('email', null, Session::get('steamId').'@nomail.contentify.org') !!}

    {!! Form::actions(['submit'], false) !!}
{!! Form::close() !!}

<script>
    $(document).ready(function()
    {
        $('#username').blur(function(event) {
            $.get(contentify.baseUrl + 'auth/username/check/' + $(this).val(), function(data)
            {
                if (data == 1) {
                    $('#username_info').removeClass('hidden');
                } else {
                    $('#username_info').addClass('hidden');
                }
            });
        });
    });
</script>