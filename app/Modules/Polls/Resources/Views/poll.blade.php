<p>
    @if ($poll->max_votes == 1)
        {{ trans('polls::vote_count_one') }}
    @else
        {{ trans('polls::vote_count_multi', [$poll->max_votes]) }}
    @endif
    {{ $poll->title }}
</p>

@if ($poll->open and user() and ! $userVoted)
    {!! Form::open(['url' => 'polls/'.$poll->id.'/confirm', 'class' => 'form-inline poll-form']) !!}
    <ul class="list-unstyled">
        @for ($counter = 1; $counter <= \App\Modules\Polls\Poll::MAX_OPTIONS; $counter++)
            @if ($poll['option'.$counter])
                <li>
                    <label>
                        @if ($poll->max_votes == 1)
                            {!! Form::radio('option', $counter) !!}
                            <span class="styled-radio"></span>
                        @else
                            {!! Form::checkbox('option'.$counter, $counter) !!}
                            <span class="styled-checkbox"></span>
                        @endif

                        {{ $poll['option'.$counter] }}
                    </label>
                </li>
            @endif
        @endfor
    </ul>

    {!! Form::actions(['submit' => trans('polls::vote_now')], false) !!}
    {!! Form::close() !!}
@else
    <?php
    $results = $poll->getResults();
    $total = array_sum($results);

    // $max(result) returns the highest value of the array.
    // max(..., 1) ensures even if no one voted yet 1 is used as maximum.
    // We need this behaviour to avoid division by zero errors.
    $maximum = max(max($results), 1);
    ?>
    <ul class="list-unstyled poll-results">
        @foreach ($results as $id => $result)
            @if ($poll['option'.$id])
                <li>
                    {{ trans('polls::votes_number', [$result]) }} {{ $poll['option'.$id] }}
                    <?php
                    $percentage = round($result / $maximum) * 100;
                    $width = ($percentage > 0) ? $percentage.'%' : '2px'; // Ensure the bar is always visible
                    ?>
                    <div class="poll-result result-number-{{ $id }}" style="width: {{ $width }}" title="{{ $percentage }}%"></div>
                </li>
            @endif
        @endforeach
    </ul>

    <p>
        {{ trans('polls::votes_total', [$total]) }}
    </p>
@endif

<p>
    @if (! $poll->open)
        {{ trans('polls::poll_closed') }}
    @endif
    @if ($userVoted)
        {{ trans('polls::already_voted') }}
    @endif
</p>