@include('visitors::chart')

<script>
    $("#chart").bind("plothover", function (event, pos, item) {
        if (item) {
            $('.days tbody tr').each(function()
            {
                if ($(this).attr('data-day') == item.datapoint[0]) {
                    if (! $(this).hasClass('hover')) $(this).addClass('hover');
                } else {
                    if ($(this).hasClass('hover')) $(this).removeClass('hover');
                }
            });
        }
    });
</script>

<table class="days table">
    <thead>
        <tr>
            <th>{!! trans('app.date') !!}</th>
            <th>{!! trans('app.object_visitors') !!}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($chart->days as $day)
            <tr data-day="{!! $day->day !!}">
                <td>{!! $day->date !!}</td>
                <td>{!! $day->visitors !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>