<form>
    <div class="form-group">
        {{ Form::label(trans('app.creator')) }}
        {{{ $msg->username }}}
    </div>
    <div class="form-group">
        {{ Form::label(trans('app.created_at')) }}
        {{ $msg->created_at }}
    </div>
    <div class="form-group">
        {{ Form::label(trans('app.email')) }}
        {{{ $msg->email }}}
    </div>
    <div class="form-group">
        {{ Form::label(trans('contact::ip')) }}
        {{ $msg->ip }}
    </div>

    <div class="form-group">
        <hr />
    </div>

    <div class="form-group">
        {{ Form::label(trans('contact::subject')) }}
        {{{ $msg->title }}}
    </div>
    <div class="form-group">
        {{ Form::label(trans('contact::message')) }}
        {{ $msg->text }}
    </div>

    {{ Form::button(trans('contact::reply'), ['onclick' => "location.href='mailto:{$msg->email}'"]) }}
</form>