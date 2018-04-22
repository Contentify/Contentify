{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.news-cats.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/news-cats', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}
    
    {!! Form::smartImageFile('image', trans('app.image')) !!}
        
    {!! Form::actions() !!}
{!! Form::close() !!}