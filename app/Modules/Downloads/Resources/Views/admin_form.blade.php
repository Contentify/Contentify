{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.downloads.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/downloads', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectRelation('downloadCat', trans('app.category'), $modelClass) !!}

    {!! Form::smartTextarea('description', trans('app.description'), true) !!}

    {!! Form::smartCheckbox('internal', trans('app.internal')) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::smartFile() !!}

    {!! Form::actions() !!}
{!! Form::close() !!}
