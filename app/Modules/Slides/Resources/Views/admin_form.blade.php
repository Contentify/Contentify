{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.slides.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/slides', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectRelation('slideCat', trans('app.category'), $modelClass, null) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartTextarea('text', trans('app.text')) !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::smartImageFile('image', trans('app.image')) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}