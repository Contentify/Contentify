{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.teams.update', $entity->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/teams', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}

    {{ Form::smartSelectRelation('teamcat', trans('app.category'), $modelName) }}

    {{ Form::smartNumeric('position', trans('app.position'), 0) }}

    {{ Form::smartImageFile() }}

    {{ Form::actions() }}
{{ Form::close() }}