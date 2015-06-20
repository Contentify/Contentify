{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.partners.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/partners', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectRelation('partnercat', 'Partner '.trans('app.category'), $modelClass, null) !!}

    {!! Form::smartTextarea('text', trans('app.text'), true) !!}

    {!! Form::smartUrl('url', trans('app.url')) !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    {!! Form::smartImageFile('image', trans('app.image')) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}