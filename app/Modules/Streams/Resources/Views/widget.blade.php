<div class="widget widget-streams">
    <ul class="list-unstyled">
        @foreach ($streams as $stream)
            <li class="{!! $stream->online ? 'online' : 'offline' !!} clearfix">
                <a href="{!! url('streams/'.$stream->id.'/'.$stream->slug) !!}" title="{{ $stream->title }}">
                    <div class="image" style="background-image: url('{!! $stream->thumbnail !!}')"></div>               
                    <span class="title">{{ $stream->title }}</span>
                    <span class="viewers">{!! HTML::fontIcon('eye').' '.$stream->viewers !!}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>