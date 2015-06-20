{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.galleries.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/galleries', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}