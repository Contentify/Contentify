<div class="create-comment">
    @if (isset($comment))
        {!! Form::model($comment, ['route' => ['comments.update', $comment->id], 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['url' => URL::route('comments.store', [$foreignType, $foreignId])]) !!}
    @endif
        {!! Form::hidden('_url', URL::current()) !!}

        <p>{!! trans('comments::write') !!}</p>

        <div class="textarea-wrapper">
            {!! Form::textarea('text') !!}
        </div>

        {!! Form::button('Save', ['class' => 'save btn btn-default']) !!}
    {!! Form::close() !!}
</div>