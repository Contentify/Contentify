{{ Form::errors($errors) }}

@if (isset($model))
{{ Form::model($model, ['route' => ['admin.adverts.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
{{ Form::open(['url' => 'admin/adverts', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}
    {{ Form::smartTextarea('code', trans('app.code')) }}
    {{ Form::smartUrl('url', trans('app.url')) }}
    {{ Form::smartNumeric('type', trans('app.type'), 0) }}
    {{ Form::smartImageFile('image', trans('app.image')) }}
        
    {{ Form::actions() }}
{{ Form::close() }}