{!! Form::errors($errors) !!}

{!! Form::open(['method' => 'put']) !!}
    {!! Form::smartNumeric('cup_points', trans('app.cup_points'), isset($cup_points) ? $cup_points : 1) !!}

    {!! Form::actions(['submit']) !!}
{!! Form::close() !!}