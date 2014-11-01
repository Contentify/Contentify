<div class="feed-messages">
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('dashboard::latest_messages') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $message)
            <tr>
                <td title="{{{ $message->text }}}">
                    <a href="{{ url($message->url) }}" target="_blank"><img src="{{ asset('icons/'.$message->icon.'.png') }}" alt="Source" width="16" height="16"> {{ date(trans('app.date_format'), $message->timestamp) }}: {{ $message->text }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>