<h1 class="page-title">{{ $cup->title }}</h1>

<div class="content">
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class="active"><a href="#home" data-toggle="tab">{!! trans('app.home') !!}</a></li>
        <li role="presentation"><a href="#particpants" data-toggle="tab">{{ trans_object('participants') }}</a></li>
        @if ($cup->start_at->timestamp < time() + (user() and user()->isSuperAdmin() ? 0 : 120))
            <li role="presentation"><a href="#matches" data-toggle="tab">{{ trans('app.object_matches') }}</a></li>
            <li role="presentation"><a href="#bracket" data-toggle="tab">{{ trans('cups::bracket') }}</a></li>
        @endif
        <li role="presentation"><a href="#rules" data-toggle="tab">{!! trans('app.rules') !!}</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            @if ($cup->image)
                <div class="image">
                    <img src="{{ $cup->uploadPath().$cup->image }}" alt="{{ $cup->title }}">
                </div>
            @endif

            <div class="actions">
                @if (user())
                    @if ($cup->join_at->timestamp > time())
                        <!-- Earlier than join_at -->
                        The cup registration is not open yet - please wait until the join phase starts.
                    @elseif ($cup->check_in_at->timestamp > time())
                        <!-- Between join_at and check_in_at -->
                        @if ($cup->isUserInCup(user()))
                            You are participating. Please wait for the check-in phase.
                            <!-- We do not need a leave button. Participants that do not check-in are ignored anyway. -->
                        @else
                            @if ($cup->forTeams())
                                @if (!$cup->teamsOfUser(user())->isEmpty())
                                    @if ($team->countMembers() < $cup->players_per_team)
                                        Click here to join the cup: 
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Join now <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($cup->teamsOfUser(user(), true) as $team)
                                                    @if ($team->countMembers() >= $cup->players_per_team)
                                                        <li><a href="{!! url('cups/join/'.$cup->id.'/'.$team->id) !!}">{{ $team->title }}</a></li>
                                                    @endif
                                                @endforeach
                                                <li><a href="{{ url('cups/teams/create') }}"><em>{{ trans('app.object_team') }}: {{ trans('app.create') }}</em></a></li>
                                            </ul>
                                        </div>
                                    @else
                                        You have no team with enough players for join the cup: <a class="btn btn-default" href="{!! url('cups/teams/create') !!}">Create team</a>
                                    @endif
                                 @else
                                    You have no team for join the cup: <a class="btn btn-default" href="{!! url('cups/teams/create') !!}">Create team</a>
                                 @endif
                            @else
                                <a class="btn btn-default" href="{!! url('cups/join/'.$cup->id.'/'.user()->id) !!}">Join now</a>
                            @endif
                        @endif
                    @elseif ($cup->start_at->timestamp > time())
                        <!-- Between check_in_at and start_at -->
                        <?php $participant = $cup->getParticipantOfUser(user()) ?>
                        @if ($participant)
                            @if ($cup->hasParticipantCheckedIn($participant))
                                Click here to check-out:
                                <a class="btn btn-default" href="{!! url('cups/check-out/'.$cup->id) !!}">Check-out now</a>
                            @else
                                Click here to check-in:
                                <a class="btn btn-default" href="{!! url('cups/check-in/'.$cup->id) !!}">Check-in now</a>
                            @endif
                        @else
                            You are not participating in this cup.
                        @endif                        
                    @elseif (! $cup->closed)
                        <!-- After start_at and cup is opened -->
                        The cup is running. Please check the brackets to see the matches.
                    @else ($cup->closed)
                        <!-- After start_at and cup is closed -->
                        The cup has been closed.
                    @endif
                @else
                    <a class="btn btn-default" href="{!! url('auth/login') !!}">Login to get access to this cup!</a>
                @endif
            </div>

            <table class="table">
                <tr>
                    <th>{{ trans('app.object_game') }}</th>
                    <td>{{ $cup->game->title }}</td>
                </tr>
                <tr>
                    <th>{{ trans('app.slots') }}</th>
                    <td>{{ $cup->countParticipants() }} / {{ $cup->slots }}</td>
                </tr>
                <tr>
                    <th>{{ trans('app.mode') }}</th>
                    <td>{{ $cup->players_per_team.'on'.$cup->players_per_team }}</td>
                </tr>
                @if ($cup->prize)
                    <tr>
                        <th>{{ trans('app.prize') }}</th>
                        <td>{{ $cup->prize }}</td>
                    </tr>
                @endif
                <tr>
                    <?php Carbon::setLocale(Config::get('app.locale')); ?>
                    <th>{{ trans('cups::join_at') }}</th>
                    <td>{{ $cup->join_at->format(trans('app.date_format').' H:i') }} ({{ $cup->join_at->diffForHumans() }})</td>
                </tr>
                <tr>
                    <th>{{ trans('cups::check_in_at') }}</th>
                    <td>{{ $cup->check_in_at->format(trans('app.date_format').' H:i') }} ({{ $cup->check_in_at->diffForHumans() }})</td>
                </tr>
                <tr>
                    <th>{{ trans('cups::start_at') }}</th>
                    <td>{{ $cup->start_at->format(trans('app.date_format').' H:i') }} ({{ $cup->start_at->diffForHumans() }})</td>
                </tr>
                @if ($cup->description)
                    <tr>
                        <th>{{ trans('app.description') }}</th>
                        <td>{!! $cup->description !!}</td>
                    </tr>
                @endif
                @if(!$cup->referees->isEmpty())
                    <tr>
                        <th>{{ trans('cups::referees') }}</th>
                        <td>
                            @foreach ($cup->referees as $key => $referee)
                                @if ($key > 0)
                                    , 
                                @endif
                                <a href="{{ url('users/'.$referee->id.'/'.$referee->slug) }}">{{ $referee->username }}</a>
                            @endforeach
                        </td>
                    </tr>
                @endif
            </table>

            {!! Comments::show('cups', $cup->id) !!}
        </div>

        <div role="tabpanel" class="tab-pane" id="particpants">
            <table class="table">
                <tr>
                    <th>{{ trans('app.name') }}</th>
                    <th>{{ trans('cups::checked_in') }}</th>
                </tr>
                @foreach ($cup->participants as $participant)
                    <tr>
                        <td>
                            @if ($cup->forTeams())
                                <a href="{{ url('cups/teams/'.$participant->id.'/'.$participant->slug) }}">{{ $participant->title }}</a>
                            @else
                                <a href="{{ url('users/'.$participant->id.'/'.$participant->slug) }}">{{ $participant->username }}</a>
                            @endif
                        </td>
                        <td>{!! $participant->pivot->checked_in ? HTML::fontIcon('check') : HTML::fontIcon('close') !!}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        
        @if ($cup->start_at->timestamp < time() + (user() and user()->isSuperAdmin() ? 0 : 120))
            <div role="tabpanel" class="tab-pane" id="matches">
                @include('cups::show_matches', compact('cup'))
            </div>

            <div role="tabpanel" class="tab-pane" id="bracket">
                @include('cups::show_bracket', compact('cup'))
            </div>
        @endif

        <div role="tabpanel" class="tab-pane" id="rules">
            <div class="text">
                {!! $cup->rulebook !!}
            </div>
        </div>
    </div>
</div>
