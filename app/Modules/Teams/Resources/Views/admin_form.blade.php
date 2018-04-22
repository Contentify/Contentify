{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.teams.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/teams', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectRelation('teamCat', trans('app.category'), $modelClass) !!}

    {!! Form::smartSelectRelation('country', trans('app.object_country'), $modelClass, null, true, true) !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    {!! Form::smartImageFile() !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}