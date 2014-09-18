@foreach ($comments as $comment)
@include('comments.comment')
@endforeach
{{ $comments->links() }}