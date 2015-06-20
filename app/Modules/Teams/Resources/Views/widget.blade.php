<div class="widget widget-teams">
    <ul class="list-unstyled">
        @foreach ($teams as $team)
            <li>
                @if ($team->image)
                    <img src="{{ $team->uploadPath().$team->image }}" alt="{{ $team->title }}">
                @endif
                {!! link_to('teams/'.$team->id.'/'.$team->slug,  $team->title) !!}
            </li>
        @endforeach
    </ul>
</div>