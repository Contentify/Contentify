{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.users.update', $entity->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/users']) }}
@endif
    {{ Form::smartCheckbox('activated', 'Activated') }}

    {{ Form::smartSelectRelation('groups', 'Permission Groups', $modelName) }}

    {{ Form::actions() }}
{{ Form::close() }}