<div class="boxer-confirm edit-member">
    {{ Form::smartText('task', 'Task', $team->pivot->task) }}
    {{ Form::smartTextarea('description', 'Description', false, $team->pivot->description) }}
    {{ Form::smartNumeric('position', 'Position', $team->pivot->position) }}
</div>