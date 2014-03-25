{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.games.update', $entity->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/games', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}

    {{ Form::smartText('short', trans('app.short_title')) }}

    {{ Form::smartIconFile() }}

    {{ Form::actions() }}
{{ Form::close() }}