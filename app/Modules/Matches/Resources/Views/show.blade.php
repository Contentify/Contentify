<h1 class="page-title">{{ trans_object('match') }}</h1>

<div class="overview clearfix">
    <div class="left">
        <img src="{!! asset('img/logo_180.png') !!}" width="120" height="120" alt="Logo">
        <div class="team-name">
            <img src="{{ asset('uploads/countries/eu.png') }}"> 
            {{ $match->left_team->title }}
        </div>
    </div>
    <div class="mid">
        {!! $match->scoreCode() !!}
    </div>
    <div class="right">
        @if ($match->right_team->image)
            <img src="{!! $match->right_team->uploadPath().$match->right_team->image !!}" width="120" height="120" alt="Logo">
        @else
            <img src="{!! asset('theme/no_opponent.jpg') !!}" width="120" height="120" alt="Logo">
        @endif
        <div class="team-name">
            @if ($match->right_team->country->icon)
                <img src="{{ $match->right_team->country->uploadPath().$match->right_team->country->icon }}">
            @endif
            {{ $match->right_team->title }}
        </div> 
    </div>
</div>
<div class="details">
    <table class="table horizontal">
        <tbody>
            <tr>
                <th>{!! trans('app.date') !!}</th>
                <td>{!! $match->played_at->dateTime() !!} - {!! $match::$states[$match->state] !!}</td>
            </tr>
            <tr>
                <th>{!! trans('Game') !!}</th>
                <td>{!! $match->game->title !!}</td>
            </tr>
            <tr>
                <th>{!! trans('Tournament') !!}</th>
                <td>{!! $match->tournament->title !!}</td>
            </tr>
            @if ($match->url)
                <tr>
                    <th>{!! trans('app.url') !!}</th>
                    <td><a href="{!! $match->url !!}" target="_blank" title="Match {!! trans('app.url') !!}">{!! $match->url !!}</a></td>
                </tr>
            @endif
            @if ($match->url)
                <tr>
                    <th>{!! trans('matches::broadcast') !!}</th>
                    <td>{!! $match->broadcast !!}</td>
                </tr>
            @endif
            @if ($match->left_lineup or $match->right_lineup)
                <tr>
                    <th>{!! trans('matches::left_lineup') !!}</th>
                    <td>{!! $match->left_lineup !!}</td>
                </tr>
                <tr>
                    <th>{!! trans('matches::right_lineup') !!}</th>
                    <td>{!! $match->right_lineup !!}</td>
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
                    <img src="{!! $matchScore->map->uploadPath().$matchScore->map->image !!}" alt="{!! $matchScore->map->title !!}">
                @endif
                <span>{!! $matchScore->map->title !!}: {!! $matchScore->left_score !!}:{!! $matchScore->right_score !!}</span>
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