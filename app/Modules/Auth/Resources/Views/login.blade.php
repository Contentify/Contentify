<h1 class="page-title">{!! trans('auth::login') !!}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => 'auth/login')) !!}
    {!! Form::smartEmail() !!}
    
    {!! Form::smartPassword() !!}

    {!! Form::actions(['submit' => trans('auth::login')], false) !!}
{!! Form::close() !!}

{!! link_to('auth/restore', trans('auth::restore_pw'), ['class' => 'btn btn-default btn-restore']) !!}