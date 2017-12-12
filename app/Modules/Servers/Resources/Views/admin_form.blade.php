{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.servers.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/servers']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartSelectRelation('game', trans('app.object_game'), $modelClass, null, true, true) !!}

    {!! Form::smartText('ip', trans('app.ip')) !!}

    {!! Form::smartText('hoster', trans('servers::hoster')) !!}

    {!! Form::smartTextarea('description', trans('app.description')) !!}

    {!! Form::smartNumeric('slots', trans('servers::slots'), 0) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}