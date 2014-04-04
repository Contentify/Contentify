{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.groups.update', $entity->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/groups']) }}
@endif
    {{ Form::smartText('name', trans('app.title')) }}

    {{ Form::hidden('permissions') }}

    @foreach ($modelName::permissions((isset($entity)) ? $entity->id : null) as $permission)
        {{ Form::smartSelect($permission->name, ucfirst($permission->name), $permission->values, $permission->current, ['class' => 'permission']) }}
    @endforeach

    {{ Form::actions() }}
{{ Form::close() }}

<script type="text/javascript">
    $(document).ready(function()
    {
        $('select.permission').change(function()
        {
            var $element    = $('[name=permissions]');
            var value       = $element.val();
            var permissions = JSON.parse(value);

            var name        = $(this).attr('name');
            value           = $(this).val();

            permissions[name] = value;

            $element.val(JSON.stringify(permissions));
        });
    });
</script>