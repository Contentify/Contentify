<h1 class="page-title">Login</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'auth/login')) }}
    {{ Form::smartEmail() }}

    {{ Form::smartPassword() }}

    {{ Form::actions(['submit' => 'Login'], false) }}
{{ Form::close() }}

{{ link_to('auth/restore', 'Restore Password') }}