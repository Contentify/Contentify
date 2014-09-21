<h1 class="page-title">Videos</h1>

<div class="videos">
    @foreach ($videos as $video)
    <div class="video">
        <a href="{{{ url('videos/'.$video->id.'/'.$video->slug) }}}">
            @if ($video->provider == 'youtube')
                <img src="http://img.youtube.com/vi/{{{ $video->permanent_id }}}/mqdefault.jpg" alt="{{{ $video->title }}}">
            @endif
            {{{ $video->title }}}
        </a>
    </div>
    @endforeach
</div>

<div class="clear"></div>
{{ $videos->links() }}