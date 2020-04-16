<div class="content-filter-ui" data-url="{!! URL::current() !!}">
    {!! Form::label('news_cat_id', trans('app.category')) !!}: {!! Form::selectForeign('news_cat_id', null, true) !!}
</div>

@forelse ($newsCollection as $news)
    <article class="news">
        <header>
            <h2>{{ $news->title }}</h2>
            <span><time>{{ $news->published_at }}</time> {!! trans('news::written_by') !!} {!! link_to('users/'.$news->creator->id.'/'.$news->creator->slug, $news->creator->username) !!} {!! trans('news::in') !!} {{ $news->newsCat->title }}</span>
        </header>
        
        <div class="content">
            @if ($news->image)
                <div class="image">
                    <a href="{!! 'news/'.$news->id.'/'.$news->slug !!}">
                        <img src="{!! $news->uploadPath().$news->image !!}" alt="{{ $news->title }}">
                    </a>
                </div>
            @elseif ($news->newsCat->image)
                <div class="image">
                    <a href="{!! 'news/'.$news->id.'/'.$news->slug !!}">
                        <img src="{!! $news->newsCat->uploadPath().$news->newsCat->image !!}" alt="{{ $news->newsCat->title }}">
                    </a>
                </div>
            @endif
            <div class="summary">
                {!! $news->summary !!}
            </div>
        </div>
        <div class="meta">
            {{ $news->countComments() }} {!! trans('app.comments') !!} - {!! link_to('news/'.$news->id.'/'.$news->slug, trans('app.read_more')) !!}
        </div>
    </article>
@empty
    <p class="space-top">{{ trans('app.nothing_here') }}</p>
@endforelse

{{ $newsCollection->links() }}
