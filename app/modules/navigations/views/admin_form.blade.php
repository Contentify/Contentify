{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.games.update', $model->id], 'files' => true, 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/games', 'files' => true]) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}

    {{-- link_to('#', 'Add') --}}

    <table id="items-table">
        <tbody>

        </tbody>
    </table>

    {{ Form::smartTextarea('items', trans('app.items')) }}

    {{ Form::actions() }}
{{ Form::close() }}

<script>
    $(document).ready(function()
    {

    });
</script>