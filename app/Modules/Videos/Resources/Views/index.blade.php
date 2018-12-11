<h1 class="page-title">{{ trans_object('videos') }}</h1>

<div class="videos clearfix">
    @forelse  ($videos as $video)
        <div class="video">
            <a href="{{ url('videos/'.$video->id.'/'.$video->slug) }}" data-id="{{ $video->id }}">
                @if ($video->provider == 'youtube')
                    <div style="background-image: url('https://img.youtube.com/vi/{{ $video->permanent_id }}/mqdefault.jpg')"></div>
                @endif

                @if ($video->provider == 'vimeo')
                    <div></div>
                    <script>
                        $.getJSON('https://vimeo.com/api/v2/video/' + {{ $video->permanent_id }} + '.json?callback=?',
                        function(data) 
                        {
                            var image = data[0].thumbnail_medium;
                            var $el = $('.page .videos [data-id=' + {{ $video->id }} + '] div');
                            $el.css('background-image', "url('" + image + "')");
                        });
                    </script>
                @endif

                {{ $video->title }}
            </a>
        </div>
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
</div>

{!! $videos->render() !!}
