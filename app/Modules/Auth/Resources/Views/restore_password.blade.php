<h1 class="page-title">{{ trans('auth::restore_pw') }}</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'auth/restore')) }}
    {{ Form::smartEmail() }}
    
    {{ Form::smartCaptcha() }}

    {{ Form::actions(['submit' => trans('app.send')], false) }}
{{ Form::close() }}