{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.downloads.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/downloads', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}
    
    {{ Form::smartSelectRelation('downloadcat', trans('app.category'), $modelClass) }}

    {{ Form::smartTextarea('description', trans('app.description'), false) }}

    {{ Form::smartFile() }}

    {{ Form::actions() }}
{{ Form::close() }}