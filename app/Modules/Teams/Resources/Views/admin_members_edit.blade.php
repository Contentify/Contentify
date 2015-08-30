{!! Form::smartText('task', trans('app.task'), $team->pivot->task) !!}

{!! Form::smartTextarea('description', trans('app.description'), false, $team->pivot->description) !!}

{!! Form::smartNumeric('position', trans('app.position'), $team->pivot->position) !!}