<div class="widget widget-matches-latest">
    <ul class="list-unstyled">
        @foreach($matches as $match)
            <li>
                <a href="{{ url('matches/'.$match->id) }}">
                    @if ($match->game->icon)
                        <img src="{{ $match->game->uploadPath().$match->game->icon }}" width="16" height="16" alt="{{ $match->game->title }}">
                    @endif
                    <span class="right-team">{{ trans('matches::vs').' '.$match->right_team->title }}</span>
                    <span class="scores">{{ $match->scoreCode() }}</span>
                    <div>
                        <small class="tournament">{{ $match->tournament->title }}</small> - 
                        <small class="date">{{ $match->played_at }}</small>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>