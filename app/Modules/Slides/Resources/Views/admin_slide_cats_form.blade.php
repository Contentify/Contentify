{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.slide-cats.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/slide-cats']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}