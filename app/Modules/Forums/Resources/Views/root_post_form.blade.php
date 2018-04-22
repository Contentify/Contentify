<h1 class="page-title">{{ trans('forums::create_thread') }}</h1>

{!! Form::errors($errors) !!}

@if (isset($forumThread))
    {!! Form::model($forumThread, ['url' => ['forums/threads/'.$forumThread->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'forums/threads/'.$forumId]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartTextarea('text', trans('app.text'), false, isset($forumPost) ? $forumPost->text : null) !!}

    {!! Form::actions(['submit'], false) !!}
{!! Form::close() !!}