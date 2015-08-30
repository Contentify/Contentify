<article class="team">
    <header>
        <h1 class="page-title inside">{{ $team->title }}</h1>

        @if ($team->image)
            <div class="image">
                <img class="img-responsive" src="{!! $team->uploadPath().$team->image !!}" alt="{{ $team->title }}">
            </div>
        @endif
    </header>

    <div class="content">
        <ul class="list-unstyled">
            @foreach ($team->users as $user)
                <li>
                    <a href="{!! url('users/'.$user->id.'/'.$user->slug) !!}">{{ $user->username }}</a>
                    @if ($user->pivot->task)
                        ({{ $user->pivot->task }})
                    @endif
                    @if ($user->pivot->description)
                        <p>
                            {!! $user->pivot->description !!}
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>

        @if (sizeof($team->awards) > 0)
            <div class="awards">
                <h2>Awards</h2>

                <table class="table" data-not-respsonsive="1">
                    <tbody>
                        @foreach ($team->awards as $award)
                            <tr>
                                <td>
                                    {!! $award->positionIcon() !!}<br>
                                </td>
                                <td>
                                    {{ $award->title }}<br>
                                </td>
                                <td>
                                    {{ $award->tournament ? $award->tournament->short : null }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</article>