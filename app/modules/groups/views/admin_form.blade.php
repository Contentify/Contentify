{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.groups.update', $entity->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/groups']) }}
@endif
    {{ Form::smartText('name', trans('app.title')) }}

    {{ Form::smartTextarea('permissions', trans('groups::permissions'), false) }}

    {{ Form::actions() }}
{{ Form::close() }}