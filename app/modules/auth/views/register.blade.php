<h1 class="page-title">Register</h1>

{{ HTML::ul($errors->all(), ['class' => 'form-errors' ]) }}

{{ Form::open(array('url' => 'auth/registration/create')) }}
    <div class="form-group">
    	{{ Form::label('username', 'Username') }}
    	{{ Form::text('username') }}
    </div>

    <div class="form-group">
    	{{ Form::label('email', 'Email') }}
    	{{ Form::email('email') }}
    </div>

    <div class="form-group">
    	{{ Form::label('password', 'Password') }}
    	{{ Form::password('password') }}
    </div>

    <div class="form-group">
    	{{ Form::label('password2', 'Password') }}
    	{{ Form::password('password2') }}
    </div>

    <div class="form-actions">
    	{{ Form::submit('Save') }}
    </div>
{{ Form::close() }}