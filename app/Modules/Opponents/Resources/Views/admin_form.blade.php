{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.opponents.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/opponents', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartText('short', trans('app.short_title')) !!}

    {!! Form::smartSelectRelation('country', 'Country', $modelClass) !!}

    {!! Form::smartText('url', trans('app.url')) !!}

    {!! Form::smartText('lineup', trans('opponents::lineup')) !!}

    {!! Form::smartImageFile() !!}

    {!! Form::actions() !!}
{!! Form::close() !!}