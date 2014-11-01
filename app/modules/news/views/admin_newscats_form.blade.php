{{ Form::errors($errors) }}

@if (isset($model))
{{ Form::model($model, ['route' => ['admin.newscats.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
{{ Form::open(['url' => 'admin/newscats', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}
    {{ Form::smartImageFile('image', trans('app.image')) }}
        
    {{ Form::actions() }}
{{ Form::close() }}