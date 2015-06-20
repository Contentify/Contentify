{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.countries.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/countries', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartText('code', trans('app.code')) !!}
    
    {!! Form::smartIconFile() !!}

    {!! Form::actions() !!}
{!! Form::close() !!}