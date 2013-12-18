<h1>Register</h1>

{{ Form::open(array('url' => 'admin/auth/registration/register')) }}
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
    	{{ Form::text('password') }}
    </div>

    <div class="form-group">
    	{{ Form::label('password2', 'Password') }}
    	{{ Form::text('password2') }}
    </div>

    <div class="form-actions">
    	{{ Form::submit('Save') }}
    </div>
{{ Form::close() }}