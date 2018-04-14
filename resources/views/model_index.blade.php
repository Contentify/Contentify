@if ($pageTitle)
    <h1 class="page-title model-index">{{ $pageTitle }}</h1>
@endif

@if ($buttons or $showSearchBox)
    <div class="toolbar top">
        {!! $buttons !!}

        @if ($showSearchBox)
            <div class="model-search-box">
                {!! Form::open(['url' => URL::current().'/search']) !!}
                        {!! Form::text('search', $searchString, ['class' => 'search-string']) !!}
                        {!! Form::submit(trans('app.search'), ['class' => 'btn btn-default']) !!}
                {!! Form::close() !!}
            </div>
        @endif
    </div>
@endif

@if ($infoText)
    <span class="info-text">{!! $infoText !!}</span>
@endif
{!! $modelTable !!}

<div class="toolbar bottom">
    {!! $recycleBin !!}
    {!! $paginator !!}
</div>