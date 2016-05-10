{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.opponents.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/opponents', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartText('short', trans('app.short_title')) !!}

    {!! Form::smartSelectRelation('country', trans('app.object_country'), $modelClass) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartText('lineup', trans('app.lineup')) !!}

    {!! Form::smartImageFile() !!}

    {!! Form::actions() !!}
{!! Form::close() !!}