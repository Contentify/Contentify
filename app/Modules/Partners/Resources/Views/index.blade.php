<h1 class="page-title model-index">{{ trans_object('partners') }}</h1>

@section('partners-index')
    @forelse ($partners as $partner)
        @include('partners::partner', compact('partner'))
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
@show
