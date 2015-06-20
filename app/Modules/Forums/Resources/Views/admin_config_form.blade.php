{!! Form::errors($errors) !!}

{!! Form::open(['method' => 'put']) !!}
    {!! Form::smartNumeric('example', trans('Example'), $example) !!}

    {!! Form::actions(['submit']) !!}
{!! Form::close() !!}