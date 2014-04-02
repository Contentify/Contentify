<h1 class="page-title">{{ trans('contact::contact') }}</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'contact/store')) }}
    {{ Form::timestamp() }}

    {{ Form::smartText('username', trans('app.name')) }}

    {{ Form::smartEmail() }}

    {{ Form::smartText('title', trans('contact::subject')) }}

    {{ Form::smartTextarea('text', trans('contact::message')) }}

    {{ Form::actions(['submit' => trans('app.send')], false) }}
{{ Form::close() }}