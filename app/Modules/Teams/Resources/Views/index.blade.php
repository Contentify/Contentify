<h1 class="page-title">{{ trans_object('teams') }}</h1>

@forelse ($teams as $team)
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
            <ul class="list-inline">
                @if ($team->users->isNotEmpty())
                    <li>{{ trans('app.lineup') }}:</li>
                    @foreach ($team->users as $user)
                        <li>
                            <a href="{!! url('users/'.$user->id.'/'.$user->slug) !!}">{{ $user->username }}</a>
                            @if ($user->pivot->task)
                                ({{ $user->pivot->task }})
                            @endif
                        </li>
                    @endforeach
                @endif
                @if ($team->country and $team->country->icon)
                    <li>
                        {{ trans('app.object_country') }}:
                        <img src="{!! $team->country->uploadPath().$team->country->icon !!}" alt="{{ $team->country->title }}" style="vertical-align: baseline">
                    </li>
                @endif
            </ul>
        </div>
    </article>
@empty
    <p>{{ trans('app.nothing_here') }}</p>
@endforelse
