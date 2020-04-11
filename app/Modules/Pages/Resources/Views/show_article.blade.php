<h1 class="page-title">{{ $article->title }}</h1>

<div class="text">
@section('pages-article-text')
    {!! $article->text !!}
@show
</div>

@if ($article->enable_comments)
    {!! Comments::show('articles', $article->id) !!}
@endif
