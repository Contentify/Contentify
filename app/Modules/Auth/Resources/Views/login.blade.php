<h1 class="page-title">{{ trans_object('login') }}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => url('auth/login'))) !!}
    {!! Form::smartEmail() !!}
    
    {!! Form::smartPassword() !!}

    <div class="form-actions">
        {!! Form::button(trans('auth::login'), ['type' => 'submit'] ) !!}
        @if (Config::get('steam-auth.api_key'))
            <a class="btn btn-default text-right" href="{{ url('auth/steam') }}" title="{{ trans('auth::login') }} (STEAM)">{!! HTML::fontIcon('steam') !!}</a>
        @endif
    </div>
{!! Form::close() !!}

{!! link_to('auth/restore', trans('auth::password_reset'), ['class' => 'btn btn-default btn-restore']) !!}
