<article id="comment{{ $comment->id }}" class="comment">
    <header>
        <h2>Comment written by {{ link_to('users/'.$comment->creator->id, $comment->creator->username) }} at {{ $comment->created_at }}</h2>
    </header>
    <div class="text">
        {{ $comment->text }}
    </div>
    @if (user())
    @if (user()->hasAccess('comments', 3) or $comment->creator->id == user()->id)
    {{ HTML::link(URL::route('comments.edit', [$comment->id]), 'Edit', ['class' => 'edit', 'data-id' => $comment->id]) }}
    @endif
    @if (user()->hasAccess('comments', 4) or $comment->creator->id == user()->id)
    {{ HTML::link(URL::route('comments.delete', [$comment->id]).'?method=DELETE', 'Delete', ['class' => 'delete', 'data-id' => $comment->id]) }}
    @endif
    @endif
</article>