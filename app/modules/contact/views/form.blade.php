<h1 class="page-title">trans('contact::contact')</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'contact/create')) }}
    <input name="_createdat" type="hidden" value="{{ time() }}">

    {{ Form::smartText('username', trans('app.name')) }}

    {{ Form::smartEmail() }}

    {{ Form::smartText('title', trans('contact::subject')) }}

    {{ Form::smartTextarea('text', trans('contact::message')) }}

    {{ Form::actions(['submit' => trans('contact::send')], false) }}
{{ Form::close() }}