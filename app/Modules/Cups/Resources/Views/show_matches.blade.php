<div class="matches">
    <?php $matches = $cup->matchesDetailed(); ?>
    <?php $round = 0; ?>

    <table class="table">
        @foreach ($matches as $match)
            @if ($match->round > $round)
                <?php $round = $match->round ?>
                <tr class="head">
                    <th colspan="4">{{ trans('app.round').' '.$round }}</th>
                </tr>
            @endif

            <tr>
                <td>
                    @include('cups::participant', ['cup' => $cup, 'participant' => $match->left_participant])
                </td>
                <td>
                    <a href="{{ url('cups/matches/'.$match->id) }}">{{ trans('matches::vs') }}</a>
                </td>
                <td>
                    @include('cups::participant', ['cup' => $cup, 'participant' => $match->right_participant])
                </td>
                <td>
                    {{ $match->left_score }}:{{ $match->right_score }}
                </td>
            </tr>
        @endforeach
    </table>
</div>