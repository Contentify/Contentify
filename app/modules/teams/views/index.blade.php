@foreach ($teams as $team)
<article class="team">
    <header>
        <a href="{{ url('teams/'.$team->id.'/'.$team->slug) }}">
            @if ($team->image)
            <div class="image">
                <img src="{{ $team->uploadPath().$team->image }}" alt="{{{ $team->title }}}">
            </div>
            @endif
            <h2>{{{ $team->title }}}</h2>
        </a>
    </header>
    <div class="content">
        <ul class="layout-v">
        @foreach ($team->users as $user)
            <li>
                <a href="{{ url('users/'.$user->id.'/'.$user->slug) }}">{{ $user->username }}</a>
                @if ($user->pivot->task)
                ({{ $user->pivot->task }})
                @endif
            </li>
        @endforeach
        </ul>
    </div>
</article>
@endforeach