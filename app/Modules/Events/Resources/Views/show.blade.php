<h1 class="page-title">{{ $event->title }}</h1>

<table class="table horizontal">
    <tbody>
        <tr>
            <th>{{ trans('app.starts_at') }}</th>
            <td>{{ $event->starts_at }}</td>
        </tr>
        @if ($event->location)
            <tr>
                <th>{{ trans('app.location') }}</th>
                <td>{{ $event->location }}</td>
            </tr>
        @endif
        @if ($event->url)
            <tr>
                <th>{{ trans('app.url') }}</th>
                <th><a href="{{ $event->url }}" target="_blank">{{ $event->title }}</a></th>
            </tr>
        @endif
        @if ($event->text)
            <tr>
                <th>{{ trans('app.text') }}</th>
                <td>{{ $event->text }}</td>
            </tr>
        @endif
    </tbody>
</table>

{!! Comments::show('events', $event->id) !!}