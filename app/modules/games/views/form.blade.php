{{ HTML::ul($errors->all(), ['class' => 'form-errors' ]) }}

@if (isset($entity))
    {{ Form::model($entity, array('route' => array('admin.games.update', $entity->id), 'method' => 'PUT')) }}
@else
    {{ Form::open(array('url' => 'admin/games')) }}
@endif
    <div class="form-group">
    	{{ Form::label('title', 'Title') }}
    	{{ Form::text('title') }}
    </div>

    <div class="form-group">
    	{{ Form::label('tag', 'Tag') }}
    	{{ Form::text('tag') }}
    </div>

    <div class="form-actions">
    	{{ Form::submit('Save') }}
    </div>
{{ Form::close() }}