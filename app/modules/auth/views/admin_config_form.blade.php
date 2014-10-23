{{ Form::errors($errors) }}

{{ Form::open(['method' => 'put']) }}
    {{ Form::smartCheckbox('registration', trans('Registration enabled'), $registration) }}

    {{ Form::smartText('unicorns', trans('Unicorns'), $unicorns) }}

    {{ Form::actions(['submit']) }}
{{ Form::close() }}