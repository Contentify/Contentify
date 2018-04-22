{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.news.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/news']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartSelectRelation('newsCat', trans('app.category'), $modelClass) !!}

    {!! Form::smartSelectRelation('creator', trans('app.author'), $modelClass, user()->id) !!}
    
    {!! Form::smartTextarea('summary', trans('news::summary'), true) !!}

    {!! Form::smartTextarea('text', trans('app.text'), true) !!}

    {!! Form::smartDateTime('published_at', trans('news::publish_at')) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::smartCheckbox('internal', trans('app.internal')) !!}
    
    {!! Form::smartCheckbox('enable_comments', trans('app.enable_comments'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}