<div class="widget widget-featured-cup">
    <a href="{{ url('cups/'.$cup->id.'/'.$cup->slug) }}" title="{{ $cup->title }}">
        <h4>{{ $cup->title }}</h4>

        @if ($cup->image)
            <img class="img-responsive" src="{{ $cup->uploadPath().$cup->image }}" alt="{{ $cup->title }}">
        @endif

        <p class="infos">
            {!! HTML::fontIcon('users') !!} {{ $cup->slots }} {{ trans('app.slots') }}, {!! HTML::fontIcon('crosshairs') !!} {{ $cup->players_per_team.'on'.$cup->players_per_team }} {{ trans('app.mode') }}, {!! HTML::fontIcon('calendar') !!} {{ $cup->start_at }}
        </p>
    </a>
</div>