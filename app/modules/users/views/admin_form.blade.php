{{ Form::errors($errors) }}

@if (isset($entity))
    {{ Form::model($entity, ['route' => ['admin.users.update', $entity->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/users']) }}
@endif
    {{ Form::smartCheckbox('activated', 'Activated') }}

    {{ Form::smartFieldOpen('Banned') }}
    @if ($entity->isBanned())
        {{ Form::checkbox('banned', 'banned', true) }}
    @else 
        {{ Form::checkbox('banned', 'banned', false) }}
    @endif
    <span class="banned-info"></span>
    {{ Form::smartFieldClose() }}

    {{ Form::smartSelectRelation('groups', 'Permission Groups', $modelName) }}

    {{ Form::actions() }}
{{ Form::close() }}

<script type="text/javascript">
    $('form [name=banned]').change(function()
    {
        var ban = +($(this).get(0).checked);
        var $self = $(this);

        $.ajax({
            url: baseUrl + 'admin/users/{{ $entity->id}}/' + ban,
            type: 'POST',
            data: { '_token': '{{ Session::token() }}' }
        }).success(function(data)
        {
            $self.get(0).checked = !!(+data);
            if (data == 1) {
                var text = 'User has been banned.';
            } else {
                var text = 'User has been unbanned.';
            }
            $('.banned-info').text(text);
        }).error(function(response)
        {
            $self.parent().html(response.responseText);
        });
    });
</script>