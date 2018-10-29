{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.polls.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/polls', 'files' => true]) !!}
@endif
{!! Form::smartText('title', trans('app.title')) !!}

{!! Form::smartCheckbox('open', trans('app.open'), true) !!}

{!! Form::smartCheckbox('internal', trans('app.internal')) !!}

{!! Form::smartNumeric('max_votes', trans('app.max_votes'), 1) !!}

{!! Form::smartText('option1', trans('app.option').' #1') !!}

{!! Form::smartText('option2', trans('app.option').' #2') !!}

{!! Form::smartText('option3', trans('app.option').' #3') !!}

{!! Form::smartText('option4', trans('app.option').' #4') !!}

{!! Form::smartText('option5', trans('app.option').' #5') !!}

{!! Form::smartText('option6', trans('app.option').' #6') !!}

{!! Form::smartText('option7', trans('app.option').' #7') !!}

{!! Form::smartText('option8', trans('app.option').' #8') !!}

{!! Form::smartText('option9', trans('app.option').' #9') !!}

{!! Form::smartText('option10', trans('app.option').' #10') !!}

{!! Form::smartText('option11', trans('app.option').' #11') !!}

{!! Form::smartText('option12', trans('app.option').' #12') !!}

{!! Form::smartText('option13', trans('app.option').' #13') !!}

{!! Form::smartText('option14', trans('app.option').' #14') !!}

{!! Form::smartText('option15', trans('app.option').' #15') !!}

{!! Form::actions() !!}
{!! Form::close() !!}