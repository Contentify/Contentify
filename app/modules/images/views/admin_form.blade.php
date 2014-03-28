{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.images.update', $entity->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/images', 'files' => true]) }}
@endif
    {{ Form::smartTagify('tags', 'Tags') }}

    {{ Form::smartImageFile() }}

    {{ Form::actions() }}
{{ Form::close() }}