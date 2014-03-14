<h1 class="page-title">Restore Password</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'auth/restore')) }}
    {{ Form::smartEmail() }}

    {{ Form::smartCaptcha() }}

    {{ Form::actions(['submit' => 'Send'], false) }}
{{ Form::close() }}