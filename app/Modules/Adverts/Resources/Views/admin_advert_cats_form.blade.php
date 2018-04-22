{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.advert-cats.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/advert-cats']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}