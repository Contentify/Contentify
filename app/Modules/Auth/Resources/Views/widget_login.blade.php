<div class="widget widget-auth-login">
    {!! Form::open(array('url' => url('auth/login', null, Config::get('app.https')))) !!}
        {!! Form::smartEmail() !!}
        
        {!! Form::smartPassword() !!}

        {!! Form::actions(['submit' => trans('auth::login')], false) !!}
    {!! Form::close() !!}

    {!! link_to('auth/restore', trans('auth::restore_pw'), ['class' => 'btn btn-default']) !!}
</div>