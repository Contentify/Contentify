{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.maps.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/maps', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}

    {{ Form::smartSelectRelation('game', 'Game', $modelClass) }}

    {{ Form::smartImageFile() }}

    {{ Form::actions() }}
{{ Form::close() }}