{{ Form::errors($errors) }}

{{ Form::open(['url' => 'admin/auth/config', 'method' => 'put']) }}
    {{ Form::smartCheckbox('registration', trans('Registration enabled'), $registration) }}

    {{ Form::smartText('unicorns', trans('Unicorns'), $unicorns) }}

    {{ Form::actions(['submit']) }}
{{ Form::close() }}