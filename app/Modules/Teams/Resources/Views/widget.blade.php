<div class="widget widget-teams">
    <ul class="list-unstyled">
        @foreach ($teams as $team)
            <li>
                <a href="{{ url('teams/'.$team->id.'/'.$team->slug) }}" title="{{ $team->title }}">
                    @if ($team->image)
                        <img src="{{ $team->uploadPath().$team->image }}" alt="{{ $team->title }}">
                    @else
                        <span class="title">{{ $team->title }}</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>