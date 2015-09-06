<h1 class="page-title">{{ trans_object('forums') }}</h1>

<div class="buttons">
    <a class="btn btn-default create" href="{!! url('forums/threads/new') !!}">{!! trans('forums::show_new') !!}</a>
</div>

@foreach($forums as $forum)
    <div class="root-forum">
        <div class="sub-forum head">
            <div class="info">
                {{ $forum->title }}
            </div>
            <div class="counter">
                Threads
            </div>
            <div class="latest">
                {!! trans('app.latest') !!}
            </div>
        </div>

        <div class="sub-forums">
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
                                {{ $subForum->latest_thread->title }}
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