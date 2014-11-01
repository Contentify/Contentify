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
        <ul class="list-unstyled">
        @foreach ($team->users as $user)
            <li>
                <a href="{{ url('users/'.$user->id.'/'.Str::slug($user->username)) }}">{{ $user->username }}</a>
                @if ($user->pivot->task)
                ({{ $user->pivot->task }})
                @endif
                @if ($user->pivot->description)
                <p>
                    {{ $user->pivot->description }}
                </p>
                @endif
            </li>
        @endforeach
        </ul>
    </div>
</article>