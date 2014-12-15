<div class="widget widget-latest-threads">
    <ul class="list-unstyled">
    @foreach ($forumThreads as $forumThread)
        <li>
            <a href="{{ url('forums/threads/'.$forumThread->id.'/'.$forumThread->slug) }}" title="{{{ $forumThread->title }}}">
                <span class="title">{{{ $forumThread->title }}}</span>
                <span class="posts-count">{{ $forumThread->posts_count }}</span>
            </a>
        </li>   
    @endforeach
    </ul>
</div>