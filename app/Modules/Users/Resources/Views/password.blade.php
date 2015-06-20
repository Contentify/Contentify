<h1 class="page-title">{!! trans('users::change_pw') !!} </h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => 'users/'.$user->id.'/password', 'method' => 'PUT')) !!}
    {!! Form::smartPassword('password_current', trans('users::current_pw')) !!}

    {!! Form::smartPassword('password', trans('users::new_pw')) !!}

    {!! Form::smartPassword('password_confirmation', trans('users::new_pw')) !!}

    {!! Form::actions(['submit'], false) !!}
{!! Form::close() !!}