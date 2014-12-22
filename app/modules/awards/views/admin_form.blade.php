{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.awards.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/awards', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}
    {{ Form::smartSelectRelation('game', 'Game', $modelClass) }}
    {{ Form::smartSelectRelation('tournament', 'Tournament', $modelClass, null, true, true) }}
    {{ Form::smartUrl('url') }}
    {{ Form::smartNumeric('position', trans('app.position'), 0) }}
    {{ Form::smartDateTime('achieved_at', trans('awards::achieved_at')) }}

    {{ Form::actions() }}
{{ Form::close() }}