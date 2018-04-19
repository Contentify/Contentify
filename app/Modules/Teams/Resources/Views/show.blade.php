<article class="team">
    <header>
        <h1 class="page-title inside">
            <a class="back" href="{!! url('teams') !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a>
            {{ $team->title }}
            @if ($team->country and $team->country->icon)
                <img src="{!! $team->country->uploadPath().$team->country->icon !!}" alt="{{ $team->country->title }}" style="vertical-align: baseline">
            @endif
        </h1>

        @if ($team->image)
            <div class="image">
                <img class="img-responsive" src="{!! $team->uploadPath().$team->image !!}" alt="{{ $team->title }}">
            </div>
        @endif
    </header>

    <div class="content">
        <ul class="list-unstyled lineup">
            @foreach ($team->users as $user)
                <li class="row">
                    <div class="col-md-4">
                        <a href="{!! url('users/'.$user->id.'/'.$user->slug) !!}"><img src="{!! $user->image ? $user->uploadPath().$user->image : asset('img/default/no_user.png') !!}" alt="{{ $user->title }}"></a>
                    </div>
                    <div class="col-md-8">
                        <h3><a href="{!! url('users/'.$user->id.'/'.$user->slug) !!}">{{ $user->username }}</a></h3>
                        @if ($user->pivot->task)
                            <h4 class="task">{{ $user->pivot->task }}</h4>
                        @endif
                        @if ($user->pivot->description)
                            <p class="description">
                                {{ $user->pivot->description }}
                            </p>
                        @endif
                        <div class="links">
                            @if (filter_var($user->facebook, FILTER_VALIDATE_URL))
                                <a class="btn" href="{{ $user->facebook }}" target="_blank">{!! HTML::fontIcon('facebook') !!}</a>
                            @else
                                <a class="btn" href="https://www.facebook.com/{{ $user->facebook }}" target="_blank">{!! HTML::fontIcon('facebook') !!}</a>
                            @endif

                            @if (filter_var($user->twitter, FILTER_VALIDATE_URL))
                                <a class="btn" href="{{ $user->twitter }}" target="_blank">{!! HTML::fontIcon('twitter') !!}</a>
                            @else
                                <a class="btn" href="https://www.twitter.com/{{ $user->twitter }}" target="_blank">{!! HTML::fontIcon('twitter') !!}</a>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        @if (sizeof($team->awards) > 0)
            <div class="awards">
                <h2>{{ trans('app.object_awards') }}</h2>

                <table class="table" data-not-respsonsive="1">
                    <tbody>
                        @foreach ($team->awards->slice(0, 5) as $award)
                            <tr>
                                <td>
                                    {!! $award->positionIcon() !!}
                                </td>
                                <td>
                                    @if ($award->url)
                                        <a href="{{ $award->url }}" target="_blank" title="{{ $award->title }}">
                                            {{ $award->title }}
                                        </a>
                                    @else
                                        {{ $award->title }}
                                    @endif
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

        @if (sizeof($team->matches) > 0)
            <div class="matches">
                <h2>{{ trans('app.object_matches') }}</h2>

                <table class="table" data-not-respsonsive="1">
                    <tbody>
                        @foreach ($team->matches->slice(0, 5) as $match)
                            <tr>
                                <td>
                                    {!! HTML::image($match->game->uploadPath().$match->game->icon, $match->game->title, ['width' => 16, 'height' => 16]) !!}
                                </td>
                                <td>
                                    <a href="{{ url('matches/'.$match->id) }}">{{ $match->right_team->title }}</a>
                                </td>
                                <td>
                                    {!! $match->scoreCode() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</article>