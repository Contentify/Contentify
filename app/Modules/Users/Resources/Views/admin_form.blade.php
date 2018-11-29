{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.users.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/users']) !!}
@endif
    {!! Form::smartCheckbox('activated', trans('users::activated'), $model->isActivated()) !!}

    {!! Form::smartCheckbox('banned', trans('users::banned'), $model->banned) !!}

    {!! Form::smartSelectRelation('roles', trans('app.object_roles'), $modelClass) !!}

    {!! Form::smartSelectRelation('teams', trans('app.object_teams'), $modelClass, null, true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}

<script type="text/javascript">
    $('form [name=activated]').change(function()
    {
        var activate = +($(this).get(0).checked);
        var $self = $(this);

        $.ajax({
            url: contentify.baseUrl + 'admin/users/{!! $model->id !!}}/' + activate,
            type: 'POST'
        }).success(function(data)
        {
            $self.get(0).checked = !!(+data);

            var text;
            if (data == 1) {
                text = '{!! trans('users::action_activated') !!}';
            } else {
                text = '{!! trans('users::action_deactivated') !!}';
            }

            contentify.modal('{!! trans('app.object_user') !!}', text);
        }).fail(function(response)
        {
            $self.parent().html('');
            contentify.alertRequestFailed(response);
        });
    });
</script>
