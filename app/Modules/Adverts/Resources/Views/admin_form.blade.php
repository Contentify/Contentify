{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.adverts.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/adverts', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartSelectRelation('advertCat', trans('app.category'), $modelClass, null) !!}

    {!! Form::smartTextarea('code', trans('app.code')) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartImageFile('image', trans('app.image')) !!}
    
    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}