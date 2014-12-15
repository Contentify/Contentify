<div class="widget widget-auth-login">
    {{ Form::open(array('url' => 'auth/login')) }}
        {{ Form::smartEmail() }}

        {{ Form::smartPassword() }}

        {{ Form::actions(['submit' => trans('auth::login')], false) }}
    {{ Form::close() }}

    {{ link_to('auth/restore', trans('auth::restore_pw')) }}
</div>