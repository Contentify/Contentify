<h1 class="page-title">
    @if (user())
        <a class="back" href="{!! url('cups/teams/overview/'.user()->id) !!}" title="{{ trans('cups::my_teams') }}">{!! HTML::fontIcon('chevron-left') !!}</a>
    @endif
    {{ trans('app.object_team') }} <em>{{ $team->title }}</em>
</h1>

@if ($team->image)
    <div class="image">
        <img src="{{ $team->uploadPath().$team->image }}" alt="{{ $team->title }}">
    </div>
@endif

@if ($team->cup_points > 0)
    <p>{!! trans('app.cup_points') !!}: {{ $team->cup_points }}</p>
@endif

@if (sizeof($team->members) > 0)
    <h2>{{ trans('app.object_members') }}</h2>
    <table class="table">
        <tr>
            <th>{{ trans('app.username') }}</th>
            <th>{{ trans('cups::organizer') }}</th>
            @if (user() and ($organizer or user()->isSuperAdmin()))
                <th>{{ trans('app.actions') }}</th>
            @endif
        </tr>
        @foreach ($team->members as $member)
            <tr>
                <td><a href="{{ url('users/'.$member->id.'/'.$member->slug) }}">{{ $member->username }}</a></td>
                <td>
                    @if (user() and ($organizer or user()->isSuperAdmin()))
                        <input type="checkbox" data-user-id="{{ $member->id }}" {{ $member->pivot->organizer ? 'checked="1"' : null }}>
                    @else
                        {!! $member->pivot->organizer ? HTML::fontIcon('check') : HTML::fontIcon('times') !!}
                    @endif
                </td>
                @if (user() and ($organizer or user()->isSuperAdmin()))
                    <td>
                        <a class="btn btn-default" href="{{ url('cups/teams/leave/'.$team->id.'/'.$member->id) }}">{{ trans('app.remove') }}</a>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
@endif

@if (sizeof($team->cups) > 0)
     <h2>{{ trans('app.object_cups') }}</h2>
    <table class="table">
        <tr>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.mode') }}</th>
            <th>{{ trans('app.object_game') }}</th>
            <th>{{ trans('cups::start_at') }}</th>
        </tr>
        @foreach ($team->cups as $cup)
            <tr>
                <td><a href="{{ url('cups/'.$cup->id.'/'.$cup->slug) }}">{{ $cup->title }}</a></td>
                <td>{{ $cup->players_per_team }}on{{ $cup->players_per_team }}</td>
                <td>{{ $cup->game->short }}</td>
                <td>{{ $cup->start_at }}</td>
            </tr>
        @endforeach
    </table>
@endif

@if ($team->invisible)
    <div class="well">{{ trans('cups::team_deleted') }}</div>
@else
    @if (user() and ! $team->isMember(user()))
        <a class="btn btn-default" href="{{ url('cups/teams/join/'.$team->id) }}">{{ trans('app.join') }}</a>
    @endif
@endif

<script>
    $(document).ready(function()
    {
        $('.page table input').change(function()
        {
            var userId = $(this).attr('data-user-id');

            $.ajax({
                url: contentify.baseUrl + 'cups/teams/organizer/{{ $team->id }}/' + userId,
                type: 'POST',
                data: {
                    organizer: this.checked ? 1 : 0
                }
            }).fail(function(response)
            {
                contentify.alertError(response.responseText);
            });
        });
    });
</script>