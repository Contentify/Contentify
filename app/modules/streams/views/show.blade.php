<h1 class="page-title">{{{ $stream->title }}}</h1>

<div class="stream-player">
    @if ($stream->provider == 'twitch')
        <iframe src="http://www.twitch.tv/{{{ $stream->permanent_id }}}/embed"></iframe>
        
        <a href="http://www.twitch.tv/{{{ $stream->permanent_id }}}?tt_medium=live_embed&amp;tt_content=text_link" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px;text-decoration:underline;">Watch live video from {{{ $stream->title }}} on www.twitch.tv</a>

        <script>
            $(document).ready(function()
            {
                var $iframe = $('.page iframe');
                $iframe.height($iframe.width() * 0.6098); // Auto-set height of the iframe
            });
        </script>
    @endif
</div>

{{ Comments::show('streams', $stream->id) }}