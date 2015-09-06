<h1 class="page-title">{{ trans_object('contact') }}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(array('url' => 'contact/store')) !!}
    {!! Form::timestamp() !!}

    {!! Form::smartText('username', trans('app.name'), user() ? user()->username : null) !!}

    {!! Form::smartEmail('email', trans('app.email'), user() ? user()->email : null) !!}

    {!! Form::smartText('title', trans('app.subject')) !!}

    {!! Form::smartTextarea('text', trans('app.message')) !!}

    {!! Form::actions(['submit' => trans('app.send')], false) !!}
{!! Form::close() !!}