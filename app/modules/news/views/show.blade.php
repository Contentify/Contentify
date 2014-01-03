<article class="news">
    <header>
        <h2>{{ $news->title }}</h2>
        <span>{{ $news->created_at }} written by {{ $news->creator->username }} in {{ $news->newscat->title }} </span>
    </header>
    <div class="content">
        <div class="image">
            <img src="{{ asset('uploads/newscats/'.$news->newscat->image) }}" alt="{{ $news->newscat->title }}">
        </div>
        <div class="intro">
            {{ $news->intro }}
        </div>
        <div class="text">
            {{ $news->text }}
        </div>
    </div>
</article>