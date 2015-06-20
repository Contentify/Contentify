<h1 class="page-title">{!! trans('auth::register') !!}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => 'auth/registration/create')) !!}
    {!! Form::smartText('username', trans('app.username')) !!}

    {!! Form::smartEmail() !!}

    {!! Form::smartPassword() !!}

    {!! Form::smartPassword('password_confirmation', trans('auth::password')) !!}
    
    {!! Form::smartCaptcha() !!}

    {!! Form::actions(['submit'], false) !!}
{!! Form::close() !!}