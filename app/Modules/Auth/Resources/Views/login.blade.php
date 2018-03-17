<h1 class="page-title">{{ trans_object('login') }}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => url('auth/login', null, is_https()))) !!}
    {!! Form::smartEmail() !!}
    
    {!! Form::smartPassword() !!}

    {!! Form::actions(['submit' => trans('auth::login')], false) !!}
{!! Form::close() !!}

{!! link_to('auth/restore', trans('auth::password_reset'), ['class' => 'btn btn-default btn-restore']) !!}