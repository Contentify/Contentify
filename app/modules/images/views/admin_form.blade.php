{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.images.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/images', 'files' => true]) }}
@endif
    {{ Form::smartTags('tags', 'Tags') }}

    {{ Form::smartImageFile() }}

    <hr>

    {{ Form::smartSelectRelation('gallery', 'Gallery', $modelClass, null, true, true) }}

    {{ Form::smartText('title', trans('app.title')) }}

    {{ Form::actions() }}
{{ Form::close() }}