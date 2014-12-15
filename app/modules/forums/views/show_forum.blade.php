<h1 class="page-title">
    {{ $forum->title }}
</h1>

<div class="buttons">
    <a class="back" href="{{ url('forums') }}">&lt;</a>
    <a class="create" href="{{ url('forums/threads/create/'.$forum->id) }}">{{ trans('forums::create_thread') }}</a>
</div>

@if (sizeof($forum->forums) > 0)
<div class="sub-forums">
    <h2>Forums</h2>
    @foreach($forum->forums as $subForum)
    <div class="sub-forum">
        <div class="info">
            <a href="{{ url('forums/'.$subForum->id.'/'.$subForum->slug) }}">
                {{{ $subForum->title }}}

                @if ($subForum->description)
                    <span class="desc">{{{ $subForum->description }}}</span>
                @endif
            </a>
        </div>
        <div class="threads">
            {{{ $subForum->threads_count }}}
        </div>
        <div class="latest">
            <a href="{{ url('forums/threads/'.$subForum->latest_thread->id.'/'.$subForum->latest_thread->slug) }}" title="{{{ $subForum->latest_thread->title }}}">
                {{{ $subForum->latest_thread->updated_at }}}
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="threads">
    <div class="thread head">
        <div class="info">
            Thread
        </div>
        <div class="posts">
            Posts
        </div>
        <div class="latest">
            Latest
        </div>
    </div>

    @foreach($forum->threads as $thread)
    @if (user() and user()->last_login < $thread->updated_at)
    <div class="thread new">
    @else
    <div class="thread old">
    @endif
        <div class="info">
            @if ($thread->sticky)
            <span class="sticky">{{ trans('forums::sticky') }}</span>
            @endif
            @if ($thread->closed)
            <span class="closed">{{ trans('forums::closed') }}</span>
            @endif
            <a href="{{ url('forums/threads/'.$thread->id.'/'.$thread->slug) }}" title="{{{ $thread->title }}}">
                {{{ $thread->title }}}
                <span class="meta">{{{ $thread->creator->username }}} - {{ $thread->created_at->dateTime() }}</span>
            </a>
        </div>
        <div class="posts">
            {{{ $thread->posts_count }}}
        </div>
        <div class="latest">
            <a href="{{ url('forums/threads/'.$thread->id.'/'.$thread->slug) }}" title="{{{ $thread->title }}}">
                {{ $thread->updated_at->dateTime() }}
                <span class="meta">{{{ $thread->creator->username }}}</span>
            </a>
        </div>
    </div>
    @endforeach
</div>