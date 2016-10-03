<div class="widget widget-matches-latest">
    <ul class="list-unstyled">
        @foreach($matches as $match)
            <li>
                <a href="{{ url('matches/'.$match->id) }}" title="{{ $match->played_at }} | {{ $match->tournament->title }}">
                    @if ($match->right_team->image)
                        <img src="{!! $match->right_team->uploadPath().$match->right_team->image !!}" width="30" height="30" alt="{{ $match->right_team->title }}">
                    @endif
                    <span class="right-team"><span class="vs">{{ trans('matches::vs') }}</span> {{ $match->right_team->title }}</span>
                    <span class="scores">{!! $match->scoreCode() !!}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>