<h2>{{ $msg->title }}</h2>

<hr>

<blockquote>{!! nl2br(e($msg->text)) !!}</blockquote>

<hr>

<p><strong>{{ trans('app.creator') }}:</strong> {{ $msg->username }} ({{ trans('app.email') }}: <a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a>, {{ trans('app.ip') }}: {{ $msg->ip }}) - <strong>{{ trans('app.created_at') }}:</strong> {!! $msg->created_at->dateTime() !!}</p>

<hr>

<div class="reply-area reply-form-hidden">
    {!! Form::button(trans('app.reply'), ['id' => 'show-reply-form']) !!}

    {!! Form::open() !!}
        {!! Form::smartTextarea('reply') !!}

        {!! Form::actions(['submit' => trans('app.reply')], false) !!}
    {!! Form::close() !!}
</div>

<script>
    $(document).ready(function()
    {
        $('.page-contact-admin-show #show-reply-form').click(function()
        {
            $('.page-contact-admin-show .reply-area').removeClass('reply-form-hidden');
        });
    });
</script>