{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.images.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/images', 'files' => true]) }}
@endif
    {{ Form::smartTags('tags', 'Tags') }}

    {{ Form::smartImageFile() }}

    {{ Form::actions() }}
{{ Form::close() }}