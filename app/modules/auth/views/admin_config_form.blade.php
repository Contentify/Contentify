{{ Form::errors($errors) }}

{{ Form::open(['method' => 'put']) }}
    {{ Form::smartCheckbox('registration', trans('auth::config_reg'), $registration) }}

    {{ Form::actions(['submit']) }}
{{ Form::close() }}