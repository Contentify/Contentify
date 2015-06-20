<h1 class="page-title">
    <a class="back" href="{!! url('forums/threads/'.$forumPost->thread->id.'/'.$forumPost->thread->slug) !!}">{{ $forumPost->thread->title }}</a>
</h1>

@include('forums::show_post', compact('forumPost'))