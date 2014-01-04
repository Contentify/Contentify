@foreach ($allNews as $news)
<article class="news">
    <header>
        <h2>{{ $news->title }}</h2>
        <span><time>{{ $news->created_at }}</time> written by {{ $news->creator->username }} in {{ $news->newscat->title }}</span>
    </header>
    <div class="content">
        @if ($news->newscat->image)
        <div class="image">
            <img src="{{ asset('uploads/newscats/'.$news->newscat->image) }}" alt="{{ $news->newscat->title }}">
        </div>
        @endif
        <div class="intro">
            {{ $news->intro }}
        </div>
    </div>
    {{ HTML::link(URL::route('news.show', [$news->id]), 'Read more...') }}
</article>
@endforeach