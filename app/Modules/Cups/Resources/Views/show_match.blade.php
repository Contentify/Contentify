<h1 class="page-title">
    <a class="back" href="{!! url('cups/'.$match->cup->id.'/'.$match->cup->slug) !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a>
    {{ trans('app.object_match') }}
</h1>

<div class="overview clearfix">
    <div class="left">
        @include('cups::participant', ['cup' => $match->cup, 'participant' => $match->left_participant, 'images' => true])
    </div>
    <div class="mid">
        {{ $match->left_score }}:{{ $match->right_score }}
    </div>
    <div class="right">
        @include('cups::participant', ['cup' => $match->cup, 'participant' => $match->right_participant, 'images' => true])
    </div>
</div>

<div class="details">
    <table class="table horizontal">
        <tbody>
            @if ($canConfirmLeft or $canConfirmRight)
                <tr>
                    <th>{!! trans('cups::confirm_score') !!}</th>
                    <td>
                        @if ($canConfirmLeft) 
                            {!! Form::open(['url' => 'cups/matches/confirm-left/'.$match->id, 'class' => 'form-inline']) !!}
                                <input type="text" class="form-control" name="left_score" value="{{ $match->left_score }}"> : <input type="text" class="form-control" name="right_score" value="{{ $match->right_score }}"> <button class="btn submit">{{ $match->with_teams ? $match->left_participant->title : $match->left_participant->username }}:  {!! trans('cups::confirm_score') !!}</button>
                            {!! Form::close() !!}
                        @endif
                        @if ($canConfirmRight)
                            {!! Form::open(['url' => 'cups/matches/confirm-right/'.$match->id, 'class' => 'form-inline']) !!}
                                <input type="text" class="form-control" name="left_score" value="{{ $match->left_score }}"> : <input type="text" class="form-control" name="right_score" value="{{ $match->right_score }}"> <button class="btn submit">{{ $match->with_teams ? $match->right_participant->title : $match->right_participant->username }}: {!! trans('cups::confirm_score') !!}</button>
                            {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
            @endif
            <tr>
                <th>{!! trans('app.object_cup') !!}</th>
                <td>
                    <a href="{{ url('cups/'.$match->cup->id.'/'.$match->cup->slug) }}">{{ $match->cup->title }}</a>
                </td>
            </tr>
            <tr>
                <th>{!! trans('app.object_game') !!}</th>
                <td>{{ $match->cup->game->title }}</td>
            </tr>
            <tr>
                <th>{!! trans('app.date') !!}</th>
                <td>{{ $match->created_at->dateTime() }}</td>
            </tr>
            <tr>
                <th>{!! trans('app.closed') !!}</th>
                <td>{!! $match->winner_id ? HTML::fontIcon('check') : HTML::fontIcon('times') !!}</td>
            </tr>
            @if ($match->next_match_id)
                <tr>
                    <th>{!! trans('cups::next_match') !!}</th>
                    <td>
                        <a href="{{ url('cups/matches/'.$match->next_match_id) }}">{{ trans('app.link') }}</a>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{!! Comments::show('cups_matches', $match->id) !!}