<h1 class="page-title">{{ $customPage->title }}</h1>

<div class="text">
@section('pages-custom-page-text')
    {!! $customPage->text !!}
@show
</div>

@if (isset($isImpressum) and $isImpressum)
    <hr>

    <h3>CMS</h3>

    <p>Powered by <a href="http://www.contentify.org/" target="_blank">Contentify CMS</a>.</p>
@endif
