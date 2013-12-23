<div class="search-box">
    {{ Form::open(['url' => URL::current().'/search']) }}
        {{ Form::text('search', $searchString, ['class' => 'search-string']) }}
        {{ Form::submit('Search') }}
    {{ Form::close() }}
</div>

{{ $contentTable }}

<div class="form-helpers">
    {{ $orderSwitcher }}
    {{ $paginator }}
</div>
