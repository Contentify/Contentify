<h1 class="page-title">{{ $stream->title }}</h1>

<div class="stream-player">
    @if ($stream->provider == 'twitch')
        <iframe src="http://www.twitch.tv/{{ $stream->permanent_id }}/embed"></iframe>
        
        <a href="http://www.twitch.tv/{{ $stream->permanent_id }}?tt_medium=live_embed&amp;tt_content=text_link">Watch live video from {{ $stream->title }} on www.twitch.tv</a>

        <script>
            $(document).ready(function()
            {
                var $iframe = $('.page iframe');
                $iframe.height($iframe.width() * 0.6098); // Auto-set height of the iframe
            });
        </script>
    @endif
    @if ($stream->provider == 'hitbox')
        <iframe src="http://hitbox.tv/#!/embed/{{ $stream->permanent_id }}" autoplay="true"></iframe>
        
        <a href="http://www.hitbox.tv/{{ $stream->permanent_id }}">Watch live video from {{ $stream->title }} on www.hitbox.tv</a>

        <script>
            $(document).ready(function()
            {
                var $iframe = $('.page iframe');
                $iframe.height($iframe.width() * 0.5625); // Auto-set height of the iframe
            });
        </script>
    @endif
</div>

{!! Comments::show('streams', $stream->id) !!}