<h1 class="page-title">{{ trans_object('streams') }}</h1>

<div class="streams clearfix">
    @forelse ($streams as $stream)
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
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
</div>

{!! $streams->render() !!}
