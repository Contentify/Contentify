<h1 class="page-title">Videos</h1>

<div class="videos clearfix">
    @foreach ($videos as $video)
        <div class="video">
            <a href="{{ url('videos/'.$video->id.'/'.$video->slug) }}" data-id="{{ $video->id }}">
                @if ($video->provider == 'youtube')
                    <div style="background-image: url('http://img.youtube.com/vi/{{ $video->permanent_id }}/mqdefault.jpg')"></div>
                @endif

                @if ($video->provider == 'vimeo')
                    <div></div>
                    <script>
                        $.getJSON('http://vimeo.com/api/v2/video/' + {{ $video->permanent_id }} + '.json?callback=?', 
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
    @endforeach
</div>

{!! $videos->render() !!}