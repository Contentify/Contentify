<article class="news">
    <header>
        <h2>{{{ $news->title }}}</h2>
        <span><time>{{ $news->created_at }}</time> {{ trans('news::written_by') }} {{ link_to('users/'.$news->creator->id, slug($news->creator->username)) }} {{ trans('news::in') }} {{{ $news->newscat->title }}}</span>
    </header>
    <div class="content">
        @if ($news->newscat->image)
        <div class="image">
            <img src="{{ $news->newscat->uploadPath().$news->newscat->image) }}" alt="{{{ $news->newscat->title }}}">
        </div>
        @endif
        <div class="intro">
            {{ $news->intro }}
        </div>
        <div class="text">
            {{ $news->text }}
        </div>
    </div>
</article>

@if ($news->enable_comments)
{{ Comments::show('news', $news->id) }}
@endif