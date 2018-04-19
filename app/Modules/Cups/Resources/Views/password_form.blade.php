<h1 class="page-title">{{ trans('app.join') }}</h1>

{!! Form::errors($errors) !!}

{!! Form::open(['url' => 'cups/teams/join/'.$team->id]) !!}
    {!! Form::smartPassword() !!}

    {!! Form::actions(['submit'], false) !!}
{!! Form::close() !!}