<h1 class="page-title">Contact</h1>

{{ HTML::ul($errors->all(), ['class' => 'form-errors' ]) }}

{{ Form::open(array('url' => 'contact/create')) }}
    <input name="_createdat" type="hidden" value="{{ time() }}">

    <div class="form-group">
    	{{ Form::label('username', 'Name') }}
    	{{ Form::text('username') }}
    </div>

    <div class="form-group">
    	{{ Form::label('email', 'Email') }}
    	{{ Form::email('email') }}
    </div>

    <div class="form-group">
        {{ Form::label('title', 'Subject') }}
        {{ Form::text('title') }}
    </div>

    <div class="form-group">
    	{{ Form::label('text', 'Message') }}
    	{{ Form::textarea('text') }}
    </div>

    <div class="form-actions">
    	{{ Form::submit('Send') }}
    </div>
{{ Form::close() }}