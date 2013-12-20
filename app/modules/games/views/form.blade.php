<div class="form-errors">
    {{ Session::get('errors') }}
</div>

@if (isset($game))
    {{ Form::model($game, array('route' => array('admin.games.update', $game->id), 'method' => 'put')) }}
@else
    {{ Form::open(array('url' => 'admin/games/create')) }}
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