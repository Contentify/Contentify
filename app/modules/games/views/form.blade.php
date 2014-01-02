{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.games.update', $entity->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/games', 'files' => true]) }}
@endif
    {{ Form::smartText('title', 'Title') }}

    {{ Form::smartText('short', 'Short Title') }}

    {{ Form::smartImageFile() }}

    {{ Form::actions() }}
{{ Form::close() }}