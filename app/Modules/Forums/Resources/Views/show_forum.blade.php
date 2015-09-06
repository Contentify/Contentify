<h1 class="page-title">
    @if ($forum->forum_id and $forum->forum->level > 0)
        <a class="back" href="{!! url('forums/'.$forum->forum->id.'/'.$forum->forum->slug) !!}">{!! HTML::fontIcon('chevron-left') !!}</a>
    @else
        <a class="back" href="{!! url('forums') !!}">{!! HTML::fontIcon('chevron-left') !!}</a>
    @endif
    {{ $forum->title }}
</h1>

<div class="buttons">
    <a class="btn btn-default create" href="{!! url('forums/threads/create/'.$forum->id) !!}">{!! trans('forums::create_thread') !!}</a>
</div>

@if (sizeof($forum->forums) > 0)
    <div class="sub-forums">
        <div class="sub-forum head">
            <div class="info">
                Forums
            </div>
            <div class="counter">
                Threads
            </div>
            <div class="latest">
                {!! trans('app.latest') !!}
            </div>
        </div>

        @foreach($forum->forums as $subForum)
            <div class="sub-forum">
                <div class="info">
                    <a href="{!! url('forums/'.$subForum->id.'/'.$subForum->slug) !!}">
                        {{ $subForum->title }}

                        @if ($subForum->description)
                            <span class="desc">{{ $subForum->description }}</span>
                        @endif
                    </a>
                </div>

                <div class="counter">
                    {{ $subForum->threads_count }}
                </div>

                <div class="latest">
                    @if ($subForum->latest_thread)
                        <a href="{!! url('forums/threads/'.$subForum->latest_thread->id.'/'.$subForum->latest_thread->slug) !!}" title="{{ $subForum->latest_thread->title }}">
                            {{ $subForum->latest_thread->updated_at }}
                        </a>
                    @else
                        -
                    @endif
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
        <div class="counter">
            Posts
        </div>
        <div class="latest">
            {!! trans('app.latest') !!}
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
                    <span class="sticky">{!! trans('forums::sticky') !!}</span>
                @endif
                @if ($thread->closed)
                    <span class="closed">{!! trans('forums::closed') !!}</span>
                @endif
                <a href="{!! url('forums/threads/'.$thread->id.'/'.$thread->slug) !!}" title="{{ $thread->title }}">
                    {{ $thread->title }}
                    <span class="meta">{{ $thread->creator->username }} - {{ $thread->created_at->dateTime() }}</span>
                </a>
            </div>
            
            <div class="counter">
                {{ $thread->posts_count }}
            </div>

            <div class="latest">
                <a href="{!! url('forums/threads/'.$thread->id.'/'.$thread->slug) !!}" title="{{ $thread->title }}">
                    {{ $thread->updated_at->dateTime() }}
                    <span class="meta">{{ $thread->creator->username }}</span>
                </a>
            </div>
        </div>
    @endforeach
</div>