@foreach ($comments as $comment)
<article id="comment{{ $comment->id }}" class="comment">
    <header>
        <h2>Comment written by {{ $comment->creator->username }} at {{ $comment->created_at }}</h2>
    </header>
    <div class="text">
        {{ $comment->text }}
    </div>
    @if (user())
    {{ HTML::link(URL::route('comments.edit', [$comment->id]), 'Edit', ['class' => 'edit', 'data-id' => $comment->id]) }}
    {{ HTML::link(URL::route('comments.delete', [$comment->id]).'?method=DELETE', 'Delete', ['class' => 'delete', 'data-id' => $comment->id]) }}
    @endif
</article>
@endforeach

{{ HTML::script('libs/comments.js') }}
<script>
var baseUrl     = '{{ Config::get('app.url') }}' + '/public/';
var foreignType = '{{ $foreignType }}';
var foreignId   = '{{ $foreignId }}';
</script>