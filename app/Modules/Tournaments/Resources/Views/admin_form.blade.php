{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.tournaments.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/tournaments', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartText('short', trans('app.short_title')) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartIconFile() !!}

    {!! Form::actions() !!}
{!! Form::close() !!}