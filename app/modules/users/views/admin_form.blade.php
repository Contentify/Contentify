{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.users.update', $entity->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/users']) }}
@endif
    {{ Form::smartCheckbox('activated', 'Activated') }}

    {{ Form::smartSelectPivot('group_id', 'Permission Groups') }}

    {{ Form::actions() }}
{{ Form::close() }}