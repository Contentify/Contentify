<div id="comments" data-foreign-type="{{ $foreignType }}" data-foreign-id="{{ $foreignId }}">
    @foreach ($comments as $comment)
        @include('comments.comment')
    @endforeach
    
    {{ $comments->links() }}
</div>

{{ HTML::script('vendor/contentify/comments.js') }}