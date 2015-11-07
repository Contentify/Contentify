<div class="widget widget-auth-login">
    {!! Form::open(array('url' => url('auth/login', null, Config::get('app.https')))) !!}
        {!! Form::smartEmail() !!}
        
        {!! Form::smartPassword() !!}

        {!! Form::actions(['submit' => trans('auth::login')], false) !!}
        <div class="form-actions">
        	<button type="submit" class="btn btn-default">{{ trans('auth::login') }}</button>

        	{!! link_to('auth/restore', trans('auth::password_reset'), ['class' => 'btn btn-default']) !!}

        </div>
    {!! Form::close() !!}

    {!! link_to('auth/restore', trans('auth::password_reset'), ['class' => 'btn btn-default']) !!}
</div>