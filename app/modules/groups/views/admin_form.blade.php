{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.groups.update', $entity->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/groups']) }}
@endif
    {{ Form::smartText('name', 'Title') }}

    {{ Form::smartTextarea('permissions', 'Permissions', false) }}

    {{ Form::actions() }}
{{ Form::close() }}