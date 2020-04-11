<h1 class="page-title">{{ trans_object('teams') }}</h1>

<ul class="list-unstyled">
    @section('cups-teams-index')
        @foreach ($teams as $team)
            <li class="clearfix">
                <a href="{{ url('cups/teams/'.$team->id.'/'.$team->slug) }}">{{ $team->title }}</a>
                @if (user())
                    <div class="actions pull-right">
                        @if ($team->isOrganizer(user()) or user()->isSuperAdmin())
                            <a class="btn btn-default" href="{{ url('cups/teams/edit/'.$team->id) }}">{{ trans('app.edit') }}</a>
                            <a class="btn btn-default" href="{{ url('cups/teams/delete/'.$team->id) }}">{{ trans('app.delete') }}</a>
                        @endif
                    </div>
                @endif
            </li>
        @endforeach
    @show
</ul>

{!! $teams->render() !!}

<div>
    <a class="btn btn-default" href="{{ url('cups/teams/create') }}">{!! HTML::fontIcon('plus') !!} {{ trans('app.new') }}</a>
</div>
