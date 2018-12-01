<div id="forum-post-id-{{ $forumPost->id }}" class="post" data-id="{{ $forumPost->id }}">
    <div class="meta">
        <a href="{!! url('users/'.$forumPost->creator->id.'/'.$forumPost->creator->slug) !!}">
            <span class="creator-name" title="{{ $forumPost->creator->username }}">{{ $forumPost->creator->username }}</span>
            @if ($forumPost->creator->avatar)
                <img class="avatar" src="{{ $forumPost->creator->uploadPath().$forumPost->creator->avatar }}" alt="{{ $forumPost->creator->username }}">
            @endif
            @if ($forumPost->creator->hasAccess('forums', PERM_UPDATE))
                <span class="label label-default">{{ trans('forums::moderator') }}</span>
            @endif
            <span class="counter">{{ $forumPost->creator->posts_count }} Posts</span>
        </a>
    </div>

    <div class="content">
        <div class="top-bar">
            <span class="date-time">{!! $forumPost->created_at->dateTime() !!}</span>
            <div class="buttons hidden-lg">
                @if (user())
                    @if (! $forumPost->thread->closed)
                        <a class="btn btn-default btn-xs quote" href="#">{!! trans('forums::quote') !!}</a>
                    @endif
                    @if (user()->hasAccess('forums', PERM_UPDATE) or $forumPost->creator->id == user()->id)
                        <a class="btn btn-default btn-xs" href="{!! url('forums/posts/edit/'.$forumPost->id) !!}">{!! trans('app.edit') !!}</a>
                    @endif
                    @if (! $forumPost->root and (user()->hasAccess('forums', PERM_DELETE) or $forumPost->creator->id == user()->id))
                        <a class="btn btn-default btn-xs" href="{!! url('forums/posts/delete/'.$forumPost->id) !!}">{!! trans('app.delete') !!}</a>
                    @endif
                    @if (Config::get('forums::reports'))
                        <a class="btn btn-default btn-xs report" href="{!! url('forums/posts/report/'.$forumPost->id) !!}">{!! trans('forums::report') !!}</a>
                    @endif
                @endif
                <a class="btn btn-default btn-xs" href="{!! url('forums/posts/perma/'.$forumPost->id) !!}">
                    @if (isset($forumPostNumber))
                        #{!! $forumPostNumber !!}
                    @else
                        #
                    @endif
                </a>
            </div>
        </div>

        <div class="text">
            {!! $forumPost->renderText() !!}

            @if ($forumPost->creator->signature)
                <div class="signature">
                    <hr>
                    {!! $forumPost->creator->renderSignature() !!}
                </div>
            @endif
        </div>
        @if ($forumPost->updater_id and $forumPost->updated_at->diffInMinutes($forumPost->created_at) > 0)
            <div class="updated">
                {!! trans('forums::updated_at', [$forumPost->updated_at->dateTime()]) !!}
            </div>
        @endif
    </div>
</div>
