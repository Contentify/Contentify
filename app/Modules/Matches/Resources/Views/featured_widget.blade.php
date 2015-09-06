<div class="widget-matches-featured">
    <a href="{{ url('matches/'.$match->id) }}">
        @if ($match->right_team->image)
            <div>
                <img src="{!! $match->right_team->uploadPath().$match->right_team->image !!}" width="100" height="100" alt="{{ $match->game->title }}">
            </div>
        @endif
        <span class="scores">{!! $match->scoreCode() !!}</span> 
        <span class="right-team">{{ trans('matches::vs').' '.$match->right_team->title }}</span>
        <div>
            <small class="tournament">{{ $match->tournament->title }}</small> - 
            <small class="date">{{ $match->played_at }}</small>
        </div>
    </a>
</div>