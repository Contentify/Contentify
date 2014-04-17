@foreach ($teams as $team)
<article class="team">
    <header>
        <a href="{{ url('teams/'.$team->id.'/'.slug($team->title)) }}">
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
                <a href="{{ url('users/'.$user->id.'/'.slug($user->username)) }}">{{ $user->username }}</a>
                @if ($user->pivot->task)
                ({{ $user->pivot->task }})
                @endif
            </li>
        @endforeach
        </ul>
    </div>
</article>
@endforeach