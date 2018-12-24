<h1 class="page-title">{{ trans_object('match') }}</h1>

<div class="overview clearfix">
    <div class="left">
        <img src="{!! asset('img/logo_180.png') !!}" alt="{{ $match->left_team->title }}">
        <div class="team-name">
            <img src="{{ asset('uploads/countries/eu.png') }}"> 
            <a href="{{ url('/teams/'.$match->left_team->id.'/'.$match->left_team->slug) }}">{{ $match->left_team->title }}</a>
        </div>
    </div>
    <div class="mid">
        {!! $match->scoreCode() !!}
    </div>
    <div class="right">
        @if ($match->right_team->image)
            <img src="{!! $match->right_team->uploadPath().$match->right_team->image !!}" alt="{{ $match->right_team->title }}">
        @else
            <img src="{!! asset('img/default/no_opponent.png') !!}" alt="{{ $match->right_team->title }}">
        @endif
        <div class="team-name">
            @if ($match->right_team->country->icon)
                <img src="{{ $match->right_team->country->uploadPath().$match->right_team->country->icon }}">
            @endif
            @if ($match->right_team->url)
                 <a href="{{ url($match->right_team->url) }}" target="_blank">{{ $match->right_team->title }}</a>
            @else
                {{ $match->right_team->title }}
            @endif
        </div> 
    </div>
</div>
<div class="details">
    <table class="table horizontal">
        <tbody>
            <tr>
                <th>{!! trans('app.date') !!}</th>
                <td>{{ $match->played_at->dateTime() }} - {{ $match::$states[$match->state] }}</td>
            </tr>
            <tr>
                <th>{!! trans('app.object_game') !!}</th>
                <td>{{ $match->game->title }}</td>
            </tr>
            <tr>
                <th>{!! trans('app.object_tournament') !!}</th>
                <td>
                    @if ($match->tournament->url)
                        <a href="{{ $match->tournament->url }}" target="_blank"  title="{{ $match->tournament->title }}">{{ $match->tournament->title }}</a>
                    @else
                        {{ $match->tournament->title }}
                    @endif
                </td>
            </tr>
            @if ($match->url)
                <tr>
                    <th>{!! trans('app.url') !!}</th>
                    <td><a href="{{ $match->url }}" target="_blank" title="{{ trans('app.object_match')}} {{ trans('app.url') }}">{{ $match->url }}</a></td>
                </tr>
            @endif
            @if ($match->broadcast)
                <tr>
                    <th>{!! trans('matches::broadcast') !!}</th>
                    <td>><a href="{{ $match->broadcast }}" target="_blank" title="{{ trans('matches::broadcast') }}">{{ $match->broadcast }}</a></td>
                </tr>
            @endif
            @if ($match->left_lineup or $match->right_lineup)
                <tr>
                    <th>{!! trans('matches::left_lineup') !!}</th>
                    <td>{{ $match->left_lineup }}</td>
                </tr>
                <tr>
                    <th>{!! trans('matches::right_lineup') !!}</th>
                    <td>{{ $match->right_lineup }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if ($match->match_scores)
    <div class="scores clearfix">
        @foreach ($match->match_scores as $matchScore)
            <div class="item">
                @if ($matchScore->map->image)
                    <img src="{!! $matchScore->map->uploadPath().$matchScore->map->image !!}" alt="{{ $matchScore->map->title }}">
                @endif
                <span>{{ $matchScore->map->title }}: {{ $matchScore->left_score }}:{{ $matchScore->right_score }}</span>
            </div>
        @endforeach
    </div>
@endif

@if ($match->text)
    <p>
        {!! $match->text !!}
    </p>
@endif

{!! Comments::show('matches', $match->id) !!}
