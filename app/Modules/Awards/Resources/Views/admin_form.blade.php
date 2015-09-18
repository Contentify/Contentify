{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.awards.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/awards', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartSelectRelation('game', trans('app.object_game'), $modelClass) !!}

    {!! Form::smartSelectRelation('tournament', trans('app.object_tournament'), $modelClass, null, true, true) !!}

    {!! Form::smartSelectRelation('team', trans('app.object_team'), $modelClass, null, true, true) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}
    
    {!! Form::smartDateTime('achieved_at', trans('app.achieved_at')) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}