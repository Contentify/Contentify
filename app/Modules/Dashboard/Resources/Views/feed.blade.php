<div class="feed-messages">
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('app.latest_msgs') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (is_array($messages))
                @foreach($messages as $message)
                    <tr>
                        <td title="{{ $message->text }}">
                            <a href="{!! url($message->url) !!}" target="_blank">{!! HTML::fontIcon($message->icon) !!} {!! date(trans('app.date_format'), $message->timestamp) !!}: {!! $message->text !!}</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>