<h1 class="page-title">{{ trans_object('teams') }} {{ trans('app.of') }} {{ $user->username }}</h1>

<ul class="list-unstyled">
    @foreach ($teams as $team)
        <li class="clearfix">
            <a href="{{ url('cups/teams/'.$team->id.'/'.$team->slug) }}">{{ $team->title }}</a>
            @if (user())
                <div class="actions pull-right">
                    @if (($team->isOrganizer($user) and user()->id == $user->id) or user()->isSuperAdmin())
                        <a class="btn btn-default" href="{{ url('cups/teams/edit/'.$team->id) }}">{{ trans('app.edit') }}</a>
                        <a class="btn btn-default" href="{{ url('cups/teams/delete/'.$team->id) }}">{{ trans('app.delete') }}</a>
                    @endif
                    @if (user()->id == $user->id or user()->isSuperAdmin())
                        <a class="btn btn-default" href="{{ url('cups/teams/leave/'.$team->id.'/'.$user->id) }}">{{ trans('app.leave') }}</a>
                    @endif
                </div>
            @endif
        </li>
    @endforeach
</ul>

<a class="btn btn-default" href="{{ url('cups/teams/create') }}">{!! HTML::fontIcon('plus') !!} {{ trans('app.new') }}</a>