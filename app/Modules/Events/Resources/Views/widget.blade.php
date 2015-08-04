<div class="widget widget-events">
    <ul class="list-unstyled">
        @foreach ($events as $event)
            <li>
                <a href="{{ url('events/'.$event->id.'/'.$event->slug) }}">{{ $event->title }}</a>
            </li>
        @endforeach
    </ul>
</div>