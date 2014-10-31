<div class="widget-streams">
    <ul class="layout-v">
    @foreach ($streams as $stream)
        <li class="{{ $stream->online ? 'online' : 'offline' }}">
            <a href="{{ url('streams/'.$stream->id.'/'.$stream->slug) }}" title="{{{ $stream->title }}}">
                <span class="title">{{{ $stream->title }}}</span>
                <span class="viewers">{{ $stream->viewers }}</span>
            </a>
        </li>   
    @endforeach
    </ul>
</div>