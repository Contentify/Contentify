{{ Form::errors($errors) }}

@if (isset($model))
{{ Form::model($model, ['route' => ['admin.slides.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
{{ Form::open(['url' => 'admin/slides', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}
    {{ Form::smartSelectRelation('slidecat', 'Slide '.trans('app.category'), $modelClass, null) }}
    {{ Form::smartUrl('url', trans('app.url')) }}
    {{ Form::smartImageFile('image', trans('app.image')) }}
        
    {{ Form::actions() }}
{{ Form::close() }}