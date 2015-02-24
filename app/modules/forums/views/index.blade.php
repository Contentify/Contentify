<h1 class="page-title">
    Forums
</h1>

<div class="buttons">
    <a class="create" href="{{ url('forums/threads/new') }}">{{ trans('forums::show_new') }}</a>
</div>

@foreach($forums as $forum)
    <div class="root-forum">
        <h2>{{{ $forum->title }}}</h2>

        <div class="sub-forums">
            @foreach($forum->forums as $subForum)
                <div class="sub-forum">
                    <div class="info">
                        <a href="{{ url('forums/'.$subForum->id.'/'.$subForum->slug) }}">
                            {{{ $subForum->title }}}

                            @if ($subForum->description)
                                <span class="desc">{{ $subForum->description }}</span>
                            @endif
                        </a>
                    </div>

                    <div class="threads">
                        {{{ $subForum->threads_count }}}
                    </div>
                    
                    <div class="latest">
                        @if ($subForum->latest_thread)
                            <a href="{{ url('forums/threads/'.$subForum->latest_thread->id.'/'.$subForum->latest_thread->slug) }}" title="{{{ $subForum->latest_thread->title }}}">
                                {{{ $subForum->latest_thread->title }}}
                                <span class="meta">{{ $subForum->latest_thread->updated_at->dateTime() }}</span>
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach