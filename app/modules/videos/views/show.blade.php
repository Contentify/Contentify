<h2>{{{ $video->title }}}</h2>

<div class="video-player">
    @if ($video->provider == 'youtube')
    <iframe src="//www.youtube.com/embed/{{{ $video->permanent_id }}}" allowfullscreen></iframe>
    <script>
        $(document).ready(function()
        {
            var $iframe = $('.page iframe');
            $iframe.height($iframe.width() * 0.5625); // Auto-set height of the iframe
        });
    </script>
    @endif
</div>

{{ Comments::show('videos', $video->id) }}