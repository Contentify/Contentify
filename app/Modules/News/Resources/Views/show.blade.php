<article class="news">
    <header>
        <h1 class="page-title inside">{{ $news->title }}</h1>
        <span><time>{{ $news->published_at }}</time> {{ trans('news::written_by') }} {!! link_to('users/'.$news->creator->id.'/'.$news->creator->slug, $news->creator->slug) !!} {{ trans('news::in') }} {{ $news->newsCat->title }}</span>
    </header>
    
    <div class="content">
        @section('news-news-image')
            @if ($news->image)
                <div class="image">
                    <img src="{!! $news->uploadPath().$news->image !!}" alt="{{ $news->title }}">
                </div>
            @elseif ($news->newsCat->image)
                <div class="image">
                    <img src="{!! $news->newsCat->uploadPath().$news->newsCat->image !!}" alt="{{ $news->newsCat->title }}">
                </div>
            @endif
        @show
        <div class="summary">
        @section('news-news-summary')
            {!! $news->summary !!}
        @show
        </div>
        <div class="text">
        @section('news-news-text')
            {!! $news->text !!}
        @show
        </div>
    </div>

    @include('share', ['shareTitle' => $news->title])
</article>

@section('news-news-comments')
    @if ($news->enable_comments)
        {!! Comments::show('news', $news->id) !!}
    @endif
@show
