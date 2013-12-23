<div class="form-helpers top">
    <div class="search-box">
        {{ Form::open(['url' => URL::current().'/search']) }}
            {{ Form::text('search', $searchString, ['class' => 'search-string']) }}
            {{ Form::submit('Search') }}
        {{ Form::close() }}
    </div>
</div>

{{ $contentTable }}

<div class="form-helpers bottom">
    {{ $orderSwitcher }}
    {{ $paginator }}
</div>
