{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.download-cats.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/download-cats']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}