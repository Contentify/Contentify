<h1 class="page-title">{{ trans('contact::application') }}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => 'application/store')) !!}
    {!! Form::timestamp() !!}

    {!! Form::smartText('username', trans('app.name'), user() ? user()->username : null) !!}

    {!! Form::smartEmail('email', trans('app.email'), user() ? user()->email : null) !!}

    {!! Form::smartSelectForeign('team_id', 'Team') !!}

    {!! Form::smartText('role', trans('app.role')) !!}

    {!! Form::smartTextarea('text', trans('contact::application')) !!}

    {!! Form::actions(['submit' => trans('app.send')], false) !!}
{!! Form::close() !!}