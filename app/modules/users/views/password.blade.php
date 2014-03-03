<h1 class="page-title">Change Password</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'users/'.$user->id.'/password', 'method' => 'PUT')) }}
    {{ Form::smartPassword('password_current', 'Current Password') }}

    {{ Form::smartPassword('password', 'New Password') }}

    {{ Form::smartPassword('password_confirmation', 'New Password') }}

    {{ Form::actions(['submit'], false) }}
{{ Form::close() }}