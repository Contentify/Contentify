<div class="bracket" style="min-width: {{ $cup->rounds() * 200 }}px">
    <?php $rows = $cup->slots // Initial value - not correct! ?>
    <?php $matches = $cup->matchesDetailed() ?>
    <?php $lastRound = '' ?>

    @for ($round = 1; $round < $cup->rounds(); $round++)
        <?php $rows = ceil($rows / 2) ?>
        <?php $roundMatches = $matches->where('round', $round) ?>

        <ul class="round round-{{ $round }}">
            @for ($row = 1; $row <= $rows; $row++)
                <?php $match = $roundMatches->where('row', $row)->pop(); ?>

                @if ($match)
                    <li class="spacer">&nbsp;</li>

                    <li class="match match-top <?php if ($match->winner_id and $match->winner_id == $match->left_participant_id) echo 'winner' ?>" data-id="{{ $match->left_participant_id }}"> 
                        @include('cups::participant', ['cup' => $cup, 'participant' => $match->left_participant])
                        <span class="score">{{ $match->left_score }}</span>
                    </li>
                    <li class="match match-spacer"><a href="{{ url('cups/matches/'.$match->id) }}">{{ trans('matches::vs') }}</a></li>
                    <li class="match match-bottom <?php if ($match->winner_id and $match->winner_id == $match->right_participant_id) echo 'winner' ?>" data-id="{{ $match->right_participant_id }}">
                        @include('cups::participant', ['cup' => $cup, 'participant' => $match->right_participant])
                        <span class="score">{{ $match->right_score }}</span>
                    </li>
                @else
                    <?php // Match not yet created ?>
                    
                    <li class="spacer">&nbsp;</li>

                    <li class="match match-top">-</li>
                    <li class="match match-spacer">{{ trans('matches::vs') }}</li>
                    <li class="match match-bottom ">-</li>
                @endif
               
                @if ($round == $cup->rounds() - 1)
                    <?php
                        // Last round (the final match)

                        $winner = '-';
                        if ($match and $match->winner_id == $match->left_participant_id) {
                            $winner = view('cups::participant', ['cup' => $cup, 'participant' => $match->left_participant]);
                        }
                        if ($match and $match->winner_id == $match->right_participant_id) {
                            $winner = view('cups::participant', ['cup' => $cup, 'participant' => $match->right_participant]);
                        }
                        $lastRound .= 
                            '<li class="spacer">&nbsp;</li>
                            <li class="match match-top winner">'.$winner.'</li>';
                    ?>
                @endif
            @endfor
            <li class="spacer">&nbsp;</li>
        </ul>
    @endfor

    <ul class="round round-{{ $round + 1 }} round-last">
        {!! $lastRound !!}
        <li class="spacer">&nbsp;</li>
    </ul>
</div>

@if (user() and user()->isSuperAdmin())
    @if ($cup->start_at->timestamp < time() and $cup->start_at->timestamp + 120 > time())
        {!! Form::open(['url' => 'cups/swap/'.$cup->id, 'class' => 'form-inline']) !!}
            <?php
                if ($cup->forTeams()) {
                    $participants = $cup->participants->pluck('title', 'id');
                } else {
                    $participants = $cup->participants->pluck('username', 'id');
                }
            ?>
            {{ trans('cups::seeding') }}: 
            {!! Form::select('first_id', $participants, null, ['class' => 'form-control']) !!}
            {!! Form::select('second_id', $participants, null, ['class' => 'form-control']) !!}
            <button class="btn submit">{!! trans('cups::swap') !!}</button><br>
            <br>
        {!! Form::close() !!}
    @else
        {!! Form::open(['url' => 'cups/matches/winner', 'class' => 'form-inline']) !!}
            <?php
                $options = [];
                foreach ($matches as $match) {
                    if ($match->right_participant and $match->winner_id) {
                        if ($cup->forTeams()) {
                            $options[$match->id] = $match->left_participant->title.' '.trans('matches::vs').' '.($match->right_participant ? $match->right_participant->title : 'Wildcard');
                        } else {
                            $options[$match->id] = $match->left_participant->username.' '.trans('matches::vs').' '.($match->right_participant ? $match->right_participant->username : 'Wildcard');
                        }
                    }
                }
            ?>
            @if (sizeof($options) > 0)
                {{ trans('cups::change_winner') }}: 
                {!! Form::select('match_id', $options, null, ['class' => 'form-control']) !!}
                <button class="btn submit">{!! trans('app.save') !!}</button><br>
                <br>
            @endif
        {!! Form::close() !!}
    @endif
@endif
