{{ Form::open(array('url' => 'auth/login')) }}
    {{ Form::smartEmail() }}

    {{ Form::smartPassword() }}

    {{ Form::actions(['submit' => 'Login'], false) }}
{{ Form::close() }}