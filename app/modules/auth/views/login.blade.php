<h1 class="page-title">Login</h1>

{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'auth/login')) }}
    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email') }}
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}
        {{ Form::text('password') }}
    </div>

    <div class="form-actions">
        {{ Form::submit('Login') }}
    </div>
{{ Form::close() }}