{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.pages.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/pages']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectForeign('page_cat_id', trans('app.type')) !!}

    {!! Form::smartTextarea('text', trans('app.text'), true) !!}

    {!! Form::smartDateTime('published_at', trans('news::publish_at')) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::smartCheckbox('internal', trans('app.internal')) !!}

    {!! Form::smartCheckbox('enable_comments', trans('app.enable_comments'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}