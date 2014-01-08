<h1 class="page-title">Contact</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'contact/create')) }}
    <input name="_createdat" type="hidden" value="{{ time() }}">

    {{ Form::smartText('username', 'Name') }}

    {{ Form::smartEmail() }}

    {{ Form::smartText('title', 'Subject') }}

    {{ Form::smartTextarea('text', 'Message') }}

    {{ Form::actions(['submit' => 'Send'], false) }}
{{ Form::close() }}