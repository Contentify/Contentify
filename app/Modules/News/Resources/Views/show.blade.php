<article class="news">
    <header>
        <h1 class="page-title inside">{{ $news->title }}</h1>
        <span><time>{{ $news->updated_at }}</time> {{ trans('news::written_by') }} {!! link_to('users/'.$news->creator->id.'/'.$news->creator->slug, $news->creator->slug) !!} {{ trans('news::in') }} {{ $news->newsCat->title }}</span>
    </header>
    
    <div class="content">
        @if ($news->newsCat->image)
            <div class="image">
                <img src="{!! $news->newsCat->uploadPath().$news->newsCat->image !!}" alt="{{ $news->newsCat->title }}">
            </div>
        @endif
        <div class="summary">
            {!! $news->summary !!}
        </div>
        <div class="text">
            {!! $news->text !!}
        </div>
    </div>

    @include('share', ['shareTitle' => $news->title])
</article>

@if ($news->enable_comments)
    {!! Comments::show('news', $news->id) !!}
@endif