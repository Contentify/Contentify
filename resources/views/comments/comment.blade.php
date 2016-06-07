<article id="comment{!! $comment->id !!}" class="comment">
    <header>
        <h3>{!! trans('comments::written_by') !!} {!! link_to('users/'.$comment->creator->id, $comment->creator->username, ['class' => 'creator-name']) !!} {!! trans('comments::written_on') !!} {!! $comment->created_at !!}</h3>
    </header>
    <div class="text">
        {!! $comment->renderText() !!}
    </div>
    @if (user())
        @if (user()->hasAccess('comments', PERM_UPDATE) or $comment->creator->id == user()->id)
            {!! HTML::link(URL::route('comments.edit', [$comment->id]), trans('app.edit'), ['class' => 'edit', 'data-id' => $comment->id]) !!}
        @endif
        @if (user())
            {!! HTML::link(URL::to('#', [$comment->id]), trans('app.quote'), ['class' => 'quote', 'data-id' => $comment->id]) !!}
        @endif
        @if (user()->hasAccess('comments', PERM_DELETE) or $comment->creator->id == user()->id)
            {!! HTML::link(URL::route('comments.delete', [$comment->id]).'?method=DELETE', trans('app.delete'), ['class' => 'delete', 'data-id' => $comment->id]) !!}
        @endif
    @endif
</article>