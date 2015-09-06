@include('messages::page_navigation', ['active' => null])

<div class="title">
    <h2>{{ $message->title }}</h2>
</div>

<hr>

@if ($message->sent_by_system)
    <div class="well system-info">
        {!! HTML::fontIcon('info') !!} {!! trans('messages::system_info') !!}
    </div>
@endif

<div class="meta">
    {!! link_to('users/'.$message->creator->id.'/'.$message->creator->slug, $message->creator->username) !!} {!! HTML::fontIcon('angle-right') !!} {!! link_to('users/'.$message->receiver->id.'/'.$message->receiver->slug, $message->receiver->username) !!} - {{ $message->created_at->dateTime() }}
</div>

<div class="message-text space-top space-bottom">
    {!! $message->renderText() !!}
</div>

<div class="actions">
    {!! Form::open(['url' => 'messages/'.$message->id, 'method' => 'delete']) !!}
        {!! button(trans('app.reply'), url('messages/reply/'.$message->id)) !!}

        {!! Form::button(trans('app.delete'), ['type' => 'submit']) !!}
    {!! Form::close() !!}
</div>