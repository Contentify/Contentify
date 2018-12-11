<h1 class="page-title">{{ trans('app.object_events') }}</h1>

@forelse ($events as $event)
    <article class="event">
        <header>
            <a href="{!! url('events/'.$event->id.'/'.$event->slug) !!}">
                <h2>{{ $event->title }}</h2>

                @if ($event->image)
                    <div class="image">
                        <img src="{!! $event->uploadPath().$event->image !!}" alt="{{ $event->title }}">
                    </div>
                @endif                
            </a>
        </header>
        
        <table class="table horizontal">
            <tbody>
                <tr>
                    <th>{!! trans('app.starts_at') !!}</th>
                    <td>{{ $event->starts_at }}</td>
                </tr>
                @if ($event->location)
                    <tr>
                        <th>{!! trans('app.location') !!}</th>
                        <td>{{ $event->location }}</td>
                    </tr>
                @endif
                @if ($event->url)
                    <tr>
                        <th>{!! trans('app.url') !!}</th>
                        <th><a href="{{ $event->url }}" target="_blank">{{ $event->title }}</a></th>
                    </tr>
                @endif
                @if ($event->text)
                    <tr>
                        <th>{!! trans('app.text') !!}</th>
                        <td>{{ $event->text }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </article>
@empty
    <p>{{ trans('app.nothing_here') }}</p>
@endforelse
