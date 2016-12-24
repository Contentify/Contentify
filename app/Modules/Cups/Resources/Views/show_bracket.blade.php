{!! HTML::script('vendor/jquery-bracket/jquery.bracket.min.js') !!}
{!! HTML::script('vendor/hoverIntent/jquery.hoverIntent.min.js') !!}
{!! HTML::style('vendor/jquery-bracket/jquery.bracket.min.css') !!}
{!! HTML::script('http://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js') !!}
{!! HTML::style('http://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css') !!}

<script type="text/javascript">
    $(document).ready(function () {
        var matchData = {
            "teams": {!! $cup->renderBracket()['teams'] !!},
            "results": {!! $cup->renderBracket()['results'] !!}
        }
        
        function Bracket() {
            var BracketWidth = $('#content').width() - 120;
            var NbSlots = {!! $cup->slots !!};
            var NbRound = Math.log(NbSlots)/Math.log(2);
            if(NbSlots < 32) {
                if($('#content').width() < 768) {
                    var RoundMargin = 25;
                    var ScoreWidth = 25;
                    var MatchMargin = 60;
                }
                else {
                    var RoundMargin = 80;
                    var ScoreWidth = 40;
                    var MatchMargin = 100;
                }
            }
            else {
                if($('#content').width() < 768) {
                    var RoundMargin = 25;
                    var ScoreWidth = 25;
                    var MatchMargin = 20;
                }
                else {
                    var RoundMargin = 55;
                    var ScoreWidth = 25;
                    var MatchMargin = 60;
                }
            }
            var TeamWidth = (BracketWidth - (( RoundMargin * ( NbRound - 1 )) + ScoreWidth * NbRound ))/NbRound;
             
            function onhover(data, hover) {
                
            }
            var BracketParameters = {
                skipConsolationRound: true,
                teamWidth: TeamWidth,
                scoreWidth: ScoreWidth,
                matchMargin: MatchMargin,
                roundMargin: RoundMargin,
                init: matchData,
                onMatchHover: onhover
            }
            setTimeout(function() {
                $('#bracket-view ').bracket(BracketParameters);
            }, 1000);

        }
        $(Bracket)
        $(window).bind('resize', function() {
            $(Bracket)
        });
    });
    
</script>
<div id="bracket-view-details"></div>
<div id="bracket-view" style="overflow: hidden;">

</div>


<?php $matches = $cup->matchesDetailed() ?>
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