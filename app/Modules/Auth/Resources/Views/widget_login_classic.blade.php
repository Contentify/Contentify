<div class="widget widget-auth-login">
    {!! Form::open(array('url' => url('auth/login', null, Config::get('app.https')))) !!}
        {!! Form::smartEmail() !!}
        
        {!! Form::smartPassword() !!}

        <div class="form-actions">
        	<button type="submit" class="btn btn-default">{{ trans('auth::login') }}</button>

        	{!! link_to('auth/restore', trans('auth::password_reset'), ['class' => 'btn btn-default']) !!}

    		<a class="btn btn-default" href="{{ url('auth/steam') }}">{!! HTML::fontIcon('steam') !!} STEAM {{ trans('auth::login') }}</a>
        </div>
    {!! Form::close() !!}
</div>