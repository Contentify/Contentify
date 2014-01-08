<div class="form-helpers top">
    {{ $buttons }}

    <div class="search-box">
        {{ Form::open(['url' => URL::current().'/search']) }}
            {{ Form::text('search', $searchString, ['class' => 'search-string']) }}
            {{ Form::submit(trans('app.search')) }}
        {{ Form::close() }}
    </div>
</div>

{{ $contentTable }}

<div class="form-helpers bottom">
    {{ $orderSwitcher }}
    {{ $paginator }}
</div>
