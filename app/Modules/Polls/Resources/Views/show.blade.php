<h1 class="page-title"><a class="back" href="{!! url('polls') !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ $poll->title }}</h1>

@if ($poll->open and user() and ! $poll->userVoted(user()))
    {!! Form::open(['url' => 'polls/'.$poll->id.'/confirm', 'class' => 'form-inline']) !!}

        @for ($counter = 1; $counter <= \App\Modules\Polls\Poll::MAX_OPTIONS; $counter++)
            @if ($poll['option'.$counter])
                <div class="poll-option">
                    @if ($poll->max_votes == 1)
                        {!! Form::radio('option'.$counter) !!} {{ $poll['option'.$counter] }}
                    @else
                        {!! Form::checkbox('option'.$counter) !!} {{ $poll['option'.$counter] }}
                    @endif
                </div>
            @endif
        @endfor

        {!! Form::actions(['submit' => trans('app.vote')], false) !!}
    {!! Form::close() !!}
@else
    @for ($counter = 1; $counter <= \App\Modules\Polls\Poll::MAX_OPTIONS; $counter++)
        @if ($poll['option'.$counter])

        @endif
    @endfor
@endif

{!! Comments::show('polls', $poll->id) !!}