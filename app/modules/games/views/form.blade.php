{{ HTML::ul($errors->all(), ['class' => 'form-errors' ]) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.games.update', $entity->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/games', 'files' => true]) }}
@endif
    <div class="form-group">
    	{{ Form::label('title', 'Title') }}
    	{{ Form::text('title') }}
    </div>

    <div class="form-group">
    	{{ Form::label('tag', 'Tag') }}
    	{{ Form::text('tag') }}
    </div>

    <div class="form-group">
        {{ Form::label('image', 'Image') }}
        {{ Form::file('image') }}
    </div>

    <div class="form-actions">
    	{{ Form::submit('Save') }}
    </div>
{{ Form::close() }}