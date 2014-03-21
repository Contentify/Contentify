<div id="comments" data-foreign-type="{{ $foreignType }}" data-foreign-id="{{ $foreignId }}">
    @foreach ($comments as $comment)
    @include('comments.comment')
    @endforeach   
</div>
{{ HTML::script('libs/comments.js') }}