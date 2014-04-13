{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.games.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/games', 'files' => true]) }}
@endif
    {{ Form::smartText('title', 'Title') }}

    {{ Form::smartText('short', 'Short Title') }}

    {{ Form::smartIconFile() }}

    {{ Form::actions() }}
{{ Form::close() }}