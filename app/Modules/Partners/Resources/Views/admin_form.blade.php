{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.partners.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/partners', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectRelation('partnerCat', trans('app.category'), $modelClass, null) !!}

    {!! Form::smartTextarea('text', trans('app.text'), true) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartText('facebook', 'Facebook') !!}

    {!! Form::smartText('twitter', 'Twitter') !!}

    {!! Form::smartText('youtube', 'YouTube') !!}

    {!! Form::smartText('discord', 'Discord') !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    {!! Form::smartImageFile('image', trans('app.image')) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}
