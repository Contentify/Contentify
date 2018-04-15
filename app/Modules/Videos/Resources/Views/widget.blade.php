<div class="widget widget-videos">
    <a href="{{ url('videos/'.$video->id.'/'.$video->slug) }}">
        @if ($video->provider == 'youtube')
            <img class="cover" src="https://img.youtube.com/vi/{{ $video->permanent_id }}/mqdefault.jpg" alt="{{ $video->title }}">
        @endif
        {{ $video->title }}
    </a>
</div>