<h1 class="page-title">{{ trans_object('teams') }}</h1>

@foreach ($teams as $team)
    <article class="team">
        <header>
            <a href="{!! url('teams/'.$team->id.'/'.$team->slug) !!}">
                <h2>{{ $team->title }}</h2>

                @if ($team->image)
                    <div class="image">
                        <img class="img-responsive" src="{!! $team->uploadPath().$team->image !!}" alt="{{ $team->title }}">
                    </div>
                @endif
            </a>
        </header>
        
        <div class="content">
            <ul class="list-unstyled">
                @foreach ($team->users as $user)
                    <li>
                        <a href="{!! url('users/'.$user->id.'/'.$user->slug) !!}">{{ $user->username }}</a>
                        @if ($user->pivot->task)
                            ({{ $user->pivot->task }})
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </article>
@endforeach