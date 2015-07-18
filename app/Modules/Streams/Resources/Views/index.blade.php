<h1 class="page-title">Streams</h1>

<div class="streams clearfix">
    @foreach ($streams as $stream)
        <div class="stream">
            <a href="{{ url('streams/'.$stream->id.'/'.$stream->slug) }}">
                @if ($stream->thumbnail)
                    <div style="background-image: url('{!! $stream->thumbnail !!}')"></div>
                @else
                    <div></div>
                @endif

                {{ $stream->title }}
            </a>
        </div>
    @endforeach
</div>

{!! $streams->render() !!}