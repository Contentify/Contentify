<div class="widget widget-cup-control">
    @if ($cups and sizeof($cups) > 0)
        <ul class="list-unstyled">
            @foreach ($cups as $cup)
                <li>
                    <h4><a href="{{ url('cups/'.$cup->id.'/'.$cup->slug) }}" title="{{ $cup->title }}">{{ $cup->title }}</a></h4>
                    
                    <p class="infos">
                        {!! HTML::fontIcon('crosshairs') !!} {{ $cup->players_per_team.'on'.$cup->players_per_team }} {{ trans('app.mode') }}, {!! HTML::fontIcon('calendar') !!} {{ $cup->start_at }}, {!! HTML::fontIcon('clock-o') !!} {{ $cup->start_at->format('H:i') }}
                    </p>
                </li>
            @endforeach
        </ul>
    @else
        You are not registered for any cups. <a href="{{ url('cups') }}">Join a cup now!</a>
    @endif

    @if (user())
        <hr>
        
        <a href="{{ url('cups/teams/overview/'.user()->id) }}">{!! HTML::fontIcon('wrench') !!} {{ trans('cups::my_teams') }}</a>
    @endif
</div>