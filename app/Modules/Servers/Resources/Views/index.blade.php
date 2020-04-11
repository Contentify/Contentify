<h1 class="page-title">{{ trans_object('servers') }}</h1>

<div class="servers clearfix">
@section('servers-index')
    @foreach ($servers as $server)
        @include('servers::server', compact('server'))
    @endforeach
@show
</div>

{!! $servers->render() !!}
