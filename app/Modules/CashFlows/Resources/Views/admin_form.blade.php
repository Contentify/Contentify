{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.cash-flows.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/cash-flows']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartTextarea('description', trans('app.note')) !!}

    {!! Form::smartNumeric('revenues', trans('app.revenues'). ' ('.Config::get('app.currency').')', '0.00', ['min' => 0, 'step' => '0.1']) !!}

    {!! Form::smartNumeric('expenses', trans('app.expenses'). ' ('.Config::get('app.currency').')', '0.00', ['min' => 0, 'step' => '0.1']) !!}

    {!! Form::smartSelectRelation('user', trans('app.person'), $modelClass, null, true, true) !!}

    {!! Form::smartDateTime('paid_at', trans('app.date')) !!}

    {!! Form::smartCheckbox('paid', trans('app.paid'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}