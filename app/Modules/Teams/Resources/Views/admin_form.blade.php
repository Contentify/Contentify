{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.teams.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/teams', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartSelectRelation('teamcat', trans('app.category'), $modelClass) !!}

    <!--{!! Form::smartTextarea('text', trans('app.text')) !!}-->

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    {!! Form::smartImageFile() !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}