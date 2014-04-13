{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.teams.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/teams', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}

    {{ Form::smartSelectRelation('teamcat', trans('app.category'), $modelClass) }}

    {{ Form::smartNumeric('position', trans('app.position'), 0) }}

    {{ Form::smartImageFile() }}

    {{ Form::actions() }}
{{ Form::close() }}