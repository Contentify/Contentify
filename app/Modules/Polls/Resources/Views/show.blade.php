<h1 class="page-title"><a class="back" href="{!! url('polls') !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ $poll->title }}</h1>

@if ($poll->open and user() and ! $poll->userVoted(user()))
    {!! Form::open(['url' => 'polls/'.$poll->id.'/confirm', 'class' => 'form-inline']) !!}

        <ul class="list-unstyled">
            @for ($counter = 1; $counter <= \App\Modules\Polls\Poll::MAX_OPTIONS; $counter++)
                @if ($poll['option'.$counter])
                    <li>
                        <label>
                            @if ($poll->max_votes == 1)
                                {!! Form::radio('option', $counter) !!}
                            @else
                                {!! Form::checkbox('option'.$counter, $counter) !!}
                            @endif

                            {{ $poll['option'.$counter] }}
                        </label>
                    </li>
                @endif
            @endfor
        </ul>

        {!! Form::actions(['submit' => trans('app.vote')], false) !!}
    {!! Form::close() !!}
@else
    @for ($counter = 1; $counter <= \App\Modules\Polls\Poll::MAX_OPTIONS; $counter++)
        @if ($poll['option'.$counter])

        @endif
    @endfor
@endif

{!! Comments::show('polls', $poll->id) !!}