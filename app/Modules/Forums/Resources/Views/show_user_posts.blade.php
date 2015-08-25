<h1 class="page-title">
    <a class="back" href="{!! url('forums') !!}">{!! HTML::fontIcon('chevron-left') !!}</a>
    {!! trans('forums::show_user_posts') !!}
</h1>

<div class="user-posts">
    @foreach($forumPosts as $forumPost)
        <div class="post-info">
            <h3>
                <a href="{!! url('forums/threads/'.$forumPost->thread->id.'/'.$forumPost->thread->slug) !!}" title="{{ $forumPost->thread->title }}">
                    {{ $forumPost->thread->title }}
                </a>
            </h3>

            <div class="preview">
                <a href="{!! $forumPost->paginatedPostUrl() !!}"><blockquote>{{ $forumPost->plainText(80) }}</blockquote></a>
            </div>

            <div class="date">
                {{ $forumPost->updated_at->dateTime() }}
            </div>
        </div>
    @endforeach
</div>

{!! $forumPosts->render() !!}