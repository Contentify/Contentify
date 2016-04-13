<h1 class="page-title">
    <a class="back" href="{!! url('forums') !!}">{!! HTML::fontIcon('chevron-left') !!}</a>
    {!! trans('forums::show_new') !!}
</h1>

@if (sizeof($forumThreads) > 0)
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

        @foreach($forumThreads as $thread)
            <div class="thread new">
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
@else
    <p>{{ trans('app.nothing_new') }}</p>
@endif