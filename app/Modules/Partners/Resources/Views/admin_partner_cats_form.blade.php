{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.partner-cats.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/partner-cats']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}