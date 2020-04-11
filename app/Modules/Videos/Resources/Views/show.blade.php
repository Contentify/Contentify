<h1 class="page-title"><a class="back" href="{!! url('videos') !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ $video->title }}</h1>

<div class="video-player">
    @section('videos-video-youtube')
        @if ($video->provider == 'youtube')
            <iframe src="//www.youtube.com/embed/{{ $video->permanent_id }}" allowfullscreen></iframe>
            <script>
                $(document).ready(function()
                {
                    var $iframe = $('.page iframe');
                    $iframe.height($iframe.width() * 0.5625); // Auto-set height of the iframe
                });
            </script>
        @endif
    @show

    @section('videos-video-vimeo')
        @if ($video->provider == 'vimeo')
            <iframe src="//player.vimeo.com/video/{{ $video->permanent_id }}" allowfullscreen></iframe> <p><a href="https://vimeo.com/{{ $video->permanent_id }}">
            <script>
                $(document).ready(function()
                {
                    var $iframe = $('.page iframe');
                    $iframe.height($iframe.width() * 0.5625); // Auto-set height of the iframe
                });
            </script>
        @endif
    @show
</div>

{!! Comments::show('videos', $video->id) !!}
