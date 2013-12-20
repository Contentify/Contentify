<h1 class="page-title">Register</h1>

{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'auth/registration/create')) }}
    <div class="form-group">
    	{{ Form::label('name', 'Name') }}
    	{{ Form::text('name') }}
    </div>

    <div class="form-group">
    	{{ Form::label('email', 'Email') }}
    	{{ Form::text('email') }}
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