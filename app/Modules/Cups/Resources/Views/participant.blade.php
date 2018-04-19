@if ($participant)
    @if (isset($images) and $images)
        @if ($participant->image)
            <img src="{!! $participant->uploadPath().$participant->image !!}" alt="{{ $participant->title }}">
        @else
            <img src="{!! $cup->forTeams() ? asset('img/default/no_opponent.png') : asset('img/default/no_user.png') !!}" alt="{{ $participant->title }}">
        @endif
    @endif

    @if ($cup->forTeams())
        <a href="{{ url('cups/teams/'.$participant->id.'/'.$participant->slug) }}">{{ $participant->title }}</a>
    @else
        <a href="{{ url('users/'.$participant->id.'/'.$participant->slug) }}">{{ $participant->username }}</a>
    @endif
@else
    <em class="wildcard">Wildcard</em>
@endif