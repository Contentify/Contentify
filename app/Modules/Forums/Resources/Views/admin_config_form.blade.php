{!! Form::errors($errors) !!}

{!! Form::open(['method' => 'put']) !!}
    {!! Form::smartCheckbox('reports', 'Forum Reports', isset($reports) ? $reports : null) !!}

    {!! Form::actions(['submit']) !!}
{!! Form::close() !!}