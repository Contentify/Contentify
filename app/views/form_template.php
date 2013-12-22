{{ HTML::ul($errors->all(), ['class' => 'form-errors' ]) }}

@if (isset($entity))
    {{ Form::model($entity, array('route' => array('admin.<?php echo($modulename) ?>.update', $entity->id), 'method' => 'PUT')) }}
@else
    {{ Form::open(array('url' => 'admin/<?php echo($modulename) ?>')) }}
@endif
    <?php foreach ($fields as $field) { ?>
    <div class="form-group">
        <?php echo($field) ?>
    </div>
    <?php } ?>
    
    <div class="form-actions">
        {{ Form::submit('Save') }}
    </div>
{{ Form::close() }}