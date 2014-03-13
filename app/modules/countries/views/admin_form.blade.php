{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.countries.update', $entity->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/countries', 'files' => true]) }}
@endif
    {{ Form::smartText('title', 'Title') }}

    {{ Form::smartText('code', 'Code') }}

    {{ Form::actions() }}
{{ Form::close() }}