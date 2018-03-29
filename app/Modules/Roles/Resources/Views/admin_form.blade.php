{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.roles.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/roles']) !!}
@endif
    {!! Form::smartText('name', trans('app.title')) !!}

    {!! Form::hidden('permissions') !!}

    @foreach ($modelClass::permissions((isset($model)) ? $model->id : null) as $permission)
        {!! Form::smartSelect($permission->name, ucfirst($permission->name), $permission->values, $permission->current, ['class' => 'permission']) !!}
    @endforeach

    {!! Form::actions() !!}
{!! Form::close() !!}

<script type="text/javascript">
    $(document).ready(function()
    {
        $('select.permission').change(function()
        {
            var $element    = $('[name=permissions]');
            var value       = $element.val();

            if (! value) {
                value = '{}'; // parse() cannot parse an empty value
            }
            var permissions = JSON.parse(value);

            var name        = $(this).attr('name');
            value           = $(this).val();

            permissions[name] = value;

            $element.val(JSON.stringify(permissions));
        });
    });
</script>