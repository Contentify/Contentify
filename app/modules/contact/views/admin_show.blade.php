<form>
    <div class="form-group">
        {{ Form::label('Creator') }}
        {{ $msg->username }}
    </div>
    <div class="form-group">
        {{ Form::label('Created At') }}
        {{ $msg->created_at }}
    </div>
    <div class="form-group">
        {{ Form::label('Email') }}
        {{ $msg->email }}
    </div>
    <div class="form-group">
        {{ Form::label('IP') }}
        {{ $msg->ip }}
    </div>

    <div class="form-group">
        <hr />
    </div>

    <div class="form-group">
        {{ Form::label('Subject') }}
        {{ $msg->title }}
    </div>
    <div class="form-group">
        {{ Form::label('Message') }}
        {{ $msg->text }}
    </div>
</form>